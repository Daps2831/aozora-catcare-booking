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
        $user = Auth::user();
        if (!$user->customer) {
            return back()->with('error', 'Profile customer tidak ditemukan.');
        }

        // 2. Enhanced validation dengan time check
        $rules = [
            'tanggalBooking' => 'required|date|after_or_equal:today',
            'jamBooking' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $tanggalBooking = $request->input('tanggalBooking');
                    $jamBooking = $value;
                    
                    // Gabungkan tanggal dan jam
                    try {
                        $bookingDateTime = Carbon::createFromFormat('Y-m-d H:i', 
                            $tanggalBooking . ' ' . $jamBooking,
                            config('app.timezone')
                        );
                        
                        $now = Carbon::now(config('app.timezone'));
                        
                        // Check jika booking datetime sudah lewat
                        if ($bookingDateTime->lte($now)) {
                            $fail('Tidak dapat booking pada waktu yang sudah lewat. Waktu saat ini: ' . $now->format('d/m/Y H:i'));
                        }
                        
                        // Check jika booking terlalu dekat (minimal 2 jam dari sekarang)
                        $minimumBookingTime = $now->copy()->addHours(2);
                        if ($bookingDateTime->lt($minimumBookingTime)) {
                            $fail('Booking minimal 2 jam dari sekarang. Waktu minimal: ' . $minimumBookingTime->format('d/m/Y H:i'));
                        }
                        
                    } catch (\Exception $e) {
                        $fail('Format tanggal atau jam tidak valid.');
                    }
                }
            ],
            'alamatBooking' => 'required|string|max:255',
            'kucing_ids' => 'required|array|min:1',
            'kucing_ids.*' => [
                'exists:kucings,id',
                function ($attribute, $value, $fail) use ($user) {
                    $kucing = \App\Models\Kucing::find($value);
                    if (!$kucing || $kucing->customer_id !== $user->customer->id) {
                        $fail('Kucing tidak ditemukan atau bukan milik Anda.');
                    }
                }
            ],
            'layanan_per_kucing' => 'required|array',
            'layanan_per_kucing.*' => 'required|integer|exists:layanans,id',
        ];

        $validatedData = $request->validate($rules);

        // 3. Double check setelah validasi
        DB::beginTransaction();
        try {
            // Parse booking datetime dengan timezone yang benar
            $bookingDateTime = Carbon::createFromFormat('Y-m-d H:i', 
                $validatedData['tanggalBooking'] . ' ' . $validatedData['jamBooking'],
                config('app.timezone')
            );
            
            $now = Carbon::now(config('app.timezone'));
            
            // Final check - mencegah race condition
            if ($bookingDateTime->lte($now)) {
                DB::rollBack();
                return back()->with('error', 'Waktu booking sudah lewat. Silakan pilih waktu yang akan datang.')
                            ->withInput();
            }

            $tanggal = $bookingDateTime->startOfDay();

            // 4. Check disabled date
            $disabledDate = \App\Models\DisabledDate::whereDate('tanggal', $tanggal->format('Y-m-d'))->first();
            if ($disabledDate) {
                DB::rollBack();
                return back()->with('error', "Tanggal tidak tersedia: {$disabledDate->keterangan}")
                            ->withInput();
            }

            // 5. Check jam operasional
            $jamOperasional = $this->getJamOperasional();
            $jamBooking = $bookingDateTime->format('H:i');
            
            if ($jamBooking < $jamOperasional['buka'] || $jamBooking > $jamOperasional['tutup']) {
                DB::rollBack();
                return back()->with('error', "Jam operasional: {$jamOperasional['buka']} - {$jamOperasional['tutup']}")
                            ->withInput();
            }

            // 6. Check kuota dengan lock
            $kucingTerdaftarHariIni = Booking::whereDate('tanggalBooking', $tanggal)
                                            ->lockForUpdate()
                                            ->withCount('kucings')
                                            ->get()
                                            ->sum('kucings_count');

            $jumlahKucingBaru = count($validatedData['kucing_ids']);

            if (($kucingTerdaftarHariIni + $jumlahKucingBaru) > 10) {
                DB::rollBack();
                return back()->with('error', 'Kuota booking penuh')->withInput();
            }

            // 7. Kalkulasi estimasi sekali
            $layananIds = array_values($validatedData['layanan_per_kucing']);
            $layanans = Layanan::whereIn('id', $layananIds)->get()->keyBy('id');
            $totalEstimasi = 0;

            foreach ($validatedData['kucing_ids'] as $kucingId) {
                $layananId = $validatedData['layanan_per_kucing'][$kucingId];
                if (isset($layanans[$layananId])) {
                    $totalEstimasi += (int) $layanans[$layananId]->estimasi_pengerjaan_per_kucing;
                }
            }

            // 8. Check bentrok tim dengan query yang aman
            $jamMulai = $tanggal->copy()->setTimeFromTimeString($validatedData['jamBooking']);
            $jamSelesai = $jamMulai->copy()->addMinutes($totalEstimasi);

            $bookingBentrok = Booking::whereDate('tanggalBooking', $tanggal)
                ->where('statusBooking', '!=', 'Selesai')
                ->where('jamBooking', '<', $jamSelesai->format('H:i'))
                ->where(DB::raw('ADDTIME(jamBooking, SEC_TO_TIME(estimasi*60))'), '>', $jamMulai->format('H:i'))
                ->lockForUpdate()
                ->count();

            $jumlahTim = \App\Models\TimGroomer::count();

            if ($bookingBentrok >= $jumlahTim) {
                DB::rollBack();
                return back()->with('error', 'Bentrok dengan jadwal lain')->withInput();
            }

            // 9. Create booking
            $booking = Booking::create([
                'customer_id' => $user->customer->id,
                'tanggalBooking' => $validatedData['tanggalBooking'],
                'jamBooking' => $validatedData['jamBooking'],
                'alamatBooking' => $validatedData['alamatBooking'],
                'statusBooking' => 'Pending',
                'estimasi' => $totalEstimasi,
            ]);

            // 10. Attach kucings
            $pivotData = [];
            foreach ($validatedData['kucing_ids'] as $kucingId) {
                $layananId = $validatedData['layanan_per_kucing'][$kucingId];
                $pivotData[$kucingId] = ['layanan_id' => $layananId];
            }
            $booking->kucings()->attach($pivotData);

            DB::commit();

            return redirect()->route('user.dashboard')
                            ->with('success', 'Booking berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Booking creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')
                        ->withInput();
        }
    }

    public function riwayat()
    {
        $user = auth()->user();
        
        // Pastikan user memiliki customer profile
        if (!$user->customer) {
            return redirect()->route('dashboard')->with('error', 'Profile customer tidak ditemukan.');
        }
        
        $customerId = $user->customer->id;
        
        // Load riwayat booking dengan pivot data (termasuk catatan)
        $riwayatBookings = Booking::where('customer_id', $customerId)
            ->with([
                'customer',
                'tim',
                'kucings' => function($query) {
                    // PENTING: Load pivot dengan layanan_id dan catatan
                    $query->withPivot('layanan_id', 'catatan');
                }
            ])
            ->orderBy('tanggalBooking', 'desc')
            ->orderBy('jamBooking', 'desc')
            ->get();
            
        return view('customer.riwayat', compact('riwayatBookings'));
    }

    public function index()
    {
        // Ambil semua booking beserta relasi kucings dan tim
        $bookings = Booking::with(['kucings', 'tim'])->get();

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

        // Ambil tanggal yang dinonaktifkan admin beserta keterangannya
        $disabledDatesData = \App\Models\DisabledDate::all()->map(function($date) {
            return [
                'date' => \Carbon\Carbon::parse($date->tanggal)->utc()->format('Y-m-d'),
                'keterangan' => $date->keterangan ?? 'Tanggal dinonaktifkan admin'
            ];
        })->keyBy('date')->toArray();

        // Ambil hanya tanggal untuk fullDates
        $disabledDates = array_keys($disabledDatesData);
        $fullDates = array_merge($fullDates, $disabledDates);

        // Siapkan events untuk FullCalendar
        $events = [];
        foreach ($bookings as $booking) {
            $start = \Carbon\Carbon::parse($booking->tanggalBooking . ' ' . $booking->jamBooking);
            $end = $start->copy()->addMinutes($booking->estimasi ?? 90);

            $events[] = [
                'title' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                'start' => $start->toDateTimeString(),
                'end'   => $end->toDateTimeString(),
                'jumlahKucing' => $booking->kucings->count(),
                'statusBooking' => $booking->statusBooking, 
                'namaTim'      => $booking->tim ? $booking->tim->nama_tim : '-',
            ];
        }

        return view('booking.index', [
            'fullDates' => $fullDates,
            'disabledDatesData' => $disabledDatesData, // Kirim data keterangan
            'events'    => $events,
        ]);
    }

    private function getJamOperasional()
{
    // Bisa diambil dari config atau database
    return [
        'buka' => '08:00',
        'tutup' => '17:00'
    ];
}
}

