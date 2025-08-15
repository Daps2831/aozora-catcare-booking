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

        // SIMPLE VALIDATION: Hanya validasi basic tanpa custom rules
        try {
            $validatedData = $request->validate([
                'tanggalBooking' => 'required|date',
                'jamBooking' => 'required|string|regex:/^\d{1,2}:\d{2}$/', // Hanya cek format
                'alamatBooking' => 'required|string|max:255',
                'kucing_ids' => 'required|array|min:1',
                'kucing_ids.*' => 'exists:kucings,id',
                'layanan_per_kucing' => 'required|array',
                'layanan_per_kucing.*' => 'required|integer|exists:layanans,id',
            ]);

            // MANUAL VALIDATION: Check operational hours manually
            $jamBooking = $validatedData['jamBooking'];
            list($hours, $minutes) = explode(':', $jamBooking);
            $hours = (int)$hours;
            $minutes = (int)$minutes;
            
            // Check basic range
            if ($hours < 8 || $hours > 18 || ($hours == 18 && $minutes > 30)) {
                return back()->withErrors(['jamBooking' => 'Jam operasional: 08:00 - 18:30'])->withInput();
            }

            // Check if today - manual validation
            $tanggalBooking = $validatedData['tanggalBooking'];
            $isToday = $tanggalBooking === now()->toDateString();
            
            if ($isToday) {
                $bookingDateTime = Carbon::createFromFormat('Y-m-d H:i', $tanggalBooking . ' ' . $jamBooking);
                $minimumTime = now()->addHours(2);
                
                if ($bookingDateTime->lt($minimumTime)) {
                    return back()->withErrors(['jamBooking' => 'Booking minimal 2 jam dari sekarang'])->withInput();
                }
            }

            // SUCCESS: Proceed with booking creation
            DB::beginTransaction();
            
            // Parse dengan timezone yang konsisten
            $bookingDateTime = Carbon::createFromFormat('Y-m-d H:i', 
                $validatedData['tanggalBooking'] . ' ' . $validatedData['jamBooking'],
                'Asia/Jakarta'
            );
            
            $now = Carbon::now('Asia/Jakarta');
            
            // Final check - mencegah race condition
            if ($bookingDateTime->lte($now)) {
                DB::rollBack();
                return back()->with('error', 'Waktu booking sudah lewat. Silakan pilih waktu yang akan datang.')
                            ->withInput();
            }

            $tanggal = $bookingDateTime->startOfDay();

            // Check disabled date
            $disabledDate = \App\Models\DisabledDate::whereDate('tanggal', $tanggal->format('Y-m-d'))->first();
            if ($disabledDate) {
                DB::rollBack();
                return back()->with('error', "Tanggal tidak tersedia: {$disabledDate->keterangan}")
                            ->withInput();
            }

            // Check kuota dengan lock untuk mencegah race condition
            $kucingTerdaftarHariIni = Booking::whereDate('tanggalBooking', $tanggal)
                                            ->lockForUpdate()
                                            ->withCount('kucings')
                                            ->get()
                                            ->sum('kucings_count');

            $jumlahKucingBaru = count($validatedData['kucing_ids']);

            if (($kucingTerdaftarHariIni + $jumlahKucingBaru) > 10) {
                DB::rollBack();
                return back()->with('error', 'Kuota booking penuh untuk tanggal tersebut. Maksimal 10 kucing per hari.')
                            ->withInput();
            }

            // Kalkulasi estimasi waktu pengerjaan
            $layananIds = array_values($validatedData['layanan_per_kucing']);
            $layanans = Layanan::whereIn('id', $layananIds)->get()->keyBy('id');
            $totalEstimasi = 0;
            $detailLayanan = [];

            foreach ($validatedData['kucing_ids'] as $kucingId) {
                $layananId = $validatedData['layanan_per_kucing'][$kucingId];
                if (isset($layanans[$layananId])) {
                    $estimasiLayanan = (int) $layanans[$layananId]->estimasi_pengerjaan_per_kucing;
                    $totalEstimasi += $estimasiLayanan;
                    $detailLayanan[$kucingId] = [
                        'layanan_id' => $layananId,
                        'nama_layanan' => $layanans[$layananId]->nama,
                        'estimasi' => $estimasiLayanan
                    ];
                }
            }

            // Pastikan minimal ada estimasi
            if ($totalEstimasi <= 0) {
                DB::rollBack();
                return back()->with('error', 'Terjadi kesalahan dalam kalkulasi estimasi waktu.')
                            ->withInput();
            }

            // Check bentrok jadwal dengan tim yang tersedia
            $jamMulai = $tanggal->copy()->setTimeFromTimeString($validatedData['jamBooking']);
            $jamSelesai = $jamMulai->copy()->addMinutes($totalEstimasi);

            // Query untuk mencari booking yang bentrok
            $bookingBentrok = Booking::whereDate('tanggalBooking', $tanggal)
                ->where('statusBooking', '!=', 'Selesai')
                ->where('statusBooking', '!=', 'Dibatalkan')
                ->where(function($query) use ($jamMulai, $jamSelesai) {
                    $query->where(function($q) use ($jamMulai, $jamSelesai) {
                        // Booking lain mulai sebelum booking ini selesai
                        $q->where('jamBooking', '<', $jamSelesai->format('H:i'))
                        ->where(DB::raw('ADDTIME(jamBooking, SEC_TO_TIME(estimasi*60))'), '>', $jamMulai->format('H:i'));
                    });
                })
                ->lockForUpdate()
                ->count();

            $jumlahTim = \App\Models\TimGroomer::count();

            if ($bookingBentrok >= $jumlahTim) {
                DB::rollBack();
                $jamSelesaiFormatted = $jamSelesai->format('H:i');
                return back()->with('error', "Jadwal bentrok dengan booking lain dan tim yang tersedia sudah ditugaskan semua pada jam tersebut. Waktu {$jamBooking} - {$jamSelesaiFormatted} tidak tersedia.")
                            ->withInput();
            }

            // Validasi apakah user sudah punya booking aktif di tanggal yang sama
            $existingBooking = Booking::where('customer_id', $user->customer->id)
                                    ->whereDate('tanggalBooking', $tanggal)
                                    ->whereIn('statusBooking', ['Pending', 'Dikonfirmasi', 'Dalam Perjalanan', 'Sedang Dikerjakan'])
                                    ->first();

            if ($existingBooking) {
                DB::rollBack();
                return back()->with('error', 'Anda sudah memiliki booking aktif pada tanggal tersebut.')
                            ->withInput();
            }

            // Create booking record
            $booking = Booking::create([
                'customer_id' => $user->customer->id,
                'tanggalBooking' => $validatedData['tanggalBooking'],
                'jamBooking' => $validatedData['jamBooking'],
                'alamatBooking' => $validatedData['alamatBooking'],
                'statusBooking' => 'Pending',
                'estimasi' => $totalEstimasi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Attach kucings dengan data layanan
            $pivotData = [];
            foreach ($validatedData['kucing_ids'] as $kucingId) {
                $layananId = $validatedData['layanan_per_kucing'][$kucingId];
                $pivotData[$kucingId] = [
                    'layanan_id' => $layananId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $booking->kucings()->attach($pivotData);

            // Log successful booking creation
            \Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'customer_id' => $user->customer->id,
                'tanggal' => $validatedData['tanggalBooking'],
                'jam' => $validatedData['jamBooking'],
                'estimasi' => $totalEstimasi,
                'jumlah_kucing' => count($validatedData['kucing_ids']),
                'detail_layanan' => $detailLayanan
            ]);

            DB::commit();

            return redirect()->route('user.dashboard')
                            ->with('success', 'Booking berhasil dibuat! Tim kami akan segera mengkonfirmasi jadwal Anda.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error dengan detail lengkap
            \Log::error('Booking creation failed', [
                'user_id' => $user->id,
                'customer_id' => $user->customer->id ?? null,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'input_data' => $request->all()
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem saat membuat booking. Silakan coba lagi atau hubungi customer service.')
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
            'tutup' => '18:30'
        ];
    }

    public function cancelBooking(Request $request, Booking $booking)
    {
        try {
            // Validasi kepemilikan booking
            if ($booking->customer_id !== auth()->user()->customer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk membatalkan booking ini'
                ], 403);
            }

            // Validasi status booking
            if ($booking->statusBooking === 'Batal' || $booking->statusBooking === 'Selesai') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking ini tidak dapat dibatalkan karena statusnya: ' . $booking->statusBooking
                ], 400);
            }

            // Validasi waktu pembatalan (minimal 2 jam sebelum jadwal)
            $bookingDateTime = \Carbon\Carbon::parse($booking->tanggalBooking . ' ' . $booking->jamBooking);
            $now = \Carbon\Carbon::now();
            $diffInHours = $now->diffInHours($bookingDateTime, false); // false = signed difference
            
            // Log untuk debugging
            \Log::info('Cancel booking validation', [
                'booking_id' => $booking->id,
                'booking_datetime' => $bookingDateTime->format('Y-m-d H:i:s'),
                'current_datetime' => $now->format('Y-m-d H:i:s'),
                'diff_hours' => $diffInHours,
                'can_cancel' => $diffInHours >= 2
            ]);
            
            if ($diffInHours < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembatalan hanya dapat dilakukan minimal 2 jam sebelum jadwal booking. Sisa waktu: ' . 
                            ($diffInHours > 0 ? number_format($diffInHours, 1) . ' jam' : 'Waktu sudah lewat')
                ], 400);
            }

            // Update status booking menjadi Batal
            $booking->update([
                'statusBooking' => 'Batal'
            ]);

            // Log successful cancellation
            \Log::info('Booking cancelled successfully', [
                'booking_id' => $booking->id,
                'customer_id' => auth()->user()->customer->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            \Log::error('Cancel booking error', [
                'booking_id' => $booking->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan booking: ' . $e->getMessage()
            ], 500);
        }
    }

}

