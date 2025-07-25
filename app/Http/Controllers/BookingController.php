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

    // Method untuk MENYIMPAN data booking
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'tanggalBooking' => 'required|date',
            'jamBooking' => 'required|string',
            'layanan_id' => 'required|exists:layanans,id',
            'kucing_ids' => 'required|array|min:1',
            'kucing_ids.*' => 'exists:kucings,id',
            'alamatBooking' => 'required|string|max:255',
        ]);

        $tanggal = Carbon::parse($validated['tanggalBooking']);
        $jumlahKucingBaru = count($validated['kucing_ids']);

        // Validasi batas 10 kucing per hari
        $kucingTerdaftarHariIni = Booking::whereDate('tanggalBooking', $tanggal)
                                        ->withCount('kucings')
                                        ->get()
                                        ->sum('kucings_count');

        if (($kucingTerdaftarHariIni + $jumlahKucingBaru) > 10) {
            return back()->with('error', 'Maaf, kuota booking untuk tanggal ini sudah penuh.');
        }

        // Hitung estimasi (contoh: 60 menit dasar + 15 menit per kucing)
        $estimasi = 60 + ($jumlahKucingBaru * 15);

        // Buat booking baru
        $booking = Booking::create([
            'customer_id' => Auth::user()->customer->id,
            'layanan_id' => $validated['layanan_id'],
            'tanggalBooking' => $validated['tanggalBooking'],
            'jamBooking' => $validated['jamBooking'], // <-- simpan jam di sini!
            'statusBooking' => 'Pending',
            'estimasi' => $estimasi,
            'alamatBooking' => $validated['alamatBooking'], // Simpan alamat booking
        ]);

        // Hubungkan booking dengan kucing-kucing yang dipilih
        $booking->kucings()->attach($validated['kucing_ids']);

        return redirect()->route('user.dashboard')->with('success', 'Booking Anda berhasil dibuat!');
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
}

