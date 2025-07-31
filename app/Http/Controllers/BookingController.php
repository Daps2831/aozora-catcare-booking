<?php

// file: app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Layanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class BookingController extends Controller
{
    public function getMonthlyBookings(Request $request)
    {
        // Validasi input bulan dan tahun
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
        ]);

        $month = $request->month;
        $year = $request->year;

        // Query untuk menghitung jumlah kucing per hari dalam satu bulan
        $bookings = Booking::withCount('kucings') // Menghitung jumlah kucing di setiap booking
            ->whereYear('tanggalBooking', $year)
            ->whereMonth('tanggalBooking', $month)
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->tanggalBooking)->format('Y-m-d'); // Kelompokkan berdasarkan tanggal
            })
            ->map(function($dayBookings) {
                return $dayBookings->sum('kucings_count'); // Jumlahkan total kucing dalam satu hari
            });

        return response()->json($bookings);
    }
    
     // Method untuk MENAMPILKAN form booking
    public function create(Request $request)
    {
        $selectedDate = $request->query('date');
        $user = Auth::user();
        $layanans = Layanan::all(); // Ambil semua layanan dari database
        
        // Ambil semua kucing milik pengguna yang sedang login
        $kucings = $user->customer->kucings;

        return view('booking.create', compact('selectedDate', 'user', 'kucings', 'layanans'));
    }

    /**
     * Method untuk MENYIMPAN data booking (Versi Lengkap dan Disempurnakan).
     */
    public function store(Request $request)
    {
        //dd($request->all()); // <-- TAMBAHKAN BARIS INI UNTUK DEBUG
        // TAHAP 1: VALIDASI TERPUSAT
        // Semua aturan validasi dan pesan kustom didefinisikan di awal.
        $rules = [
            'tanggalBooking'    => 'required|date',
            'jamBooking'        => 'required|date_format:H:i',
            'alamatBooking'     => 'required|string|max:255',
            'kucing_ids'        => 'required|array|min:1',
            'kucing_ids.*'      => 'exists:kucings,id', // Pastikan setiap ID kucing ada di tabel kucings
            'layanan_per_kucing'=> 'required|array',
            'layanan_per_kucing.*' => 'required|integer|exists:layanans,id',
        ];

        $messages = [
            'kucing_ids.required'           => 'Anda harus memilih setidaknya satu kucing untuk booking.',
            'kucing_ids.min'                => 'Anda harus memilih setidaknya satu kucing untuk booking.',
            'layanan_per_kucing.*.required' => 'Silakan pilih layanan untuk setiap kucing yang dicentang.',
            'layanan_per_kucing.*.exists'   => 'Layanan yang Anda pilih tidak valid.',
        ];

        $validatedData = $request->validate($rules, $messages);


        // TAHAP 2: VALIDASI LOGIKA BISNIS (KUOTA HARIAN)
        // Dilakukan setelah validasi dasar berhasil untuk efisiensi.
        $tanggal = Carbon::parse($validatedData['tanggalBooking']);
        $jumlahKucingBaru = count($validatedData['kucing_ids']);

        $kucingTerdaftarHariIni = Booking::whereDate('tanggalBooking', $tanggal)
                                        ->withCount('kucings')
                                        ->get()
                                        ->sum('kucings_count');

        if (($kucingTerdaftarHariIni + $jumlahKucingBaru) > 10) {
            return back()->with('error', 'Maaf, kuota booking untuk tanggal yang dipilih sudah penuh atau tidak mencukupi.')->withInput();
        }


        // TAHAP 3: PENYIMPANAN DATA DENGAN TRANSAKSI
        // Memastikan semua data berhasil disimpan atau tidak sama sekali.
        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'customer_id'   => Auth::user()->customer->id,
                'tanggalBooking'=> $validatedData['tanggalBooking'],
                'jamBooking'    => $validatedData['jamBooking'],
                'alamatBooking' => $validatedData['alamatBooking'],
                'statusBooking' => 'Pending',
                'estimasi'      => 90, // Anda bisa menambahkan logika perhitungan estimasi di sini
            ]);

            // Siapkan data untuk tabel pivot `booking_kucing`
            $pivotData = [];
            foreach ($validatedData['kucing_ids'] as $kucingId) {
                // Ambil layanan_id yang sesuai dari array layanan_per_kucing
                $layananId = $validatedData['layanan_per_kucing'][$kucingId];
                $pivotData[$kucingId] = ['layanan_id' => $layananId];
            }

            // Simpan relasi ke tabel pivot dalam satu perintah
            $booking->kucings()->attach($pivotData);

            DB::commit(); // Konfirmasi semua operasi database jika berhasil

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua operasi jika terjadi kesalahan

            // Mengembalikan pengguna dengan pesan error umum
            return redirect()->back()->with('error', 'Terjadi kesalahan pada sistem saat membuat booking. Silakan coba lagi.')->withInput();
        }

        // TAHAP 4: REDIRECT SETELAH SUKSES
        return redirect()->route('user.dashboard')->with('success', 'Booking Anda telah berhasil dibuat!');
    }

    public function riwayat()
    {
        $user = auth()->user();
        $customerId = $user->customer->id ?? $user->id;
        $riwayatBookings = Booking::where('customer_id', $customerId)
            ->with(['layanan', 'kucings'])
            ->latest()
            ->get();
        return view('customer.riwayat', compact('riwayatBookings'));
    }

    public function index()
    {
        // Ambil semua booking
        $bookings = Booking::all();

        // Siapkan array untuk tanggal penuh (kuota >= 10)
        $bookingsPerTanggal = Booking::withCount('kucings')
            ->select('tanggalBooking')
            ->get()
            ->groupBy('tanggalBooking')
            ->map(function($items) {
                return $items->sum('kucings_count');
            });

        $fullDates = [];
        foreach ($bookingsPerTanggal as $tanggal => $jumlah) {
            if ($jumlah >= 10) $fullDates[] = $tanggal;
        }

        // Siapkan events untuk FullCalendar
        $events = [];
        foreach ($bookings as $booking) {
            // Hitung jam selesai
            $start = \Carbon\Carbon::parse($booking->tanggalBooking . ' ' . $booking->jamBooking);
            $end = $start->copy()->addMinutes($booking->estimasi ?? 90);

            $events[] = [
                'title' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                'start' => $start->toDateTimeString(),
                'end'   => $end->toDateTimeString(),
            ];
        }

        return view('booking.index', [
            'fullDates' => $fullDates,
            'events'    => $events,
        ]);
    }
}

