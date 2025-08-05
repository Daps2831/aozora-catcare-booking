<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\TimGroomer;
use Illuminate\Pagination\LengthAwarePaginator;


class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['kucings', 'tim', 'customer']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('statusBooking', $request->status);
        }       

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggalBooking', $request->tanggal);
        }

        $perPage = $request->input('per_page', 10);

        // Jika search layanan, ambil semua dulu, filter di PHP, lalu paginasi manual
        if ($request->filled('q')) {
            $search = strtolower($request->q);

            // Ambil semua data dulu
            $allBookings = $query->get();

            $filtered = $allBookings->filter(function($booking) use ($search) {
                // Cek customer
                $customerMatch = $booking->customer && stripos(strtolower($booking->customer->name), $search) !== false;

                // Cek kucing
                $kucingMatch = $booking->kucings->contains(function($kucing) use ($search) {
                    return stripos(strtolower($kucing->nama_kucing), $search) !== false;
                });

                // Cek layanan (pivot relasi)
                $layananMatch = $booking->kucings->contains(function($kucing) use ($search) {
                    if (isset($kucing->pivot->layanan_id)) {
                        $layanan = \App\Models\Layanan::find($kucing->pivot->layanan_id);
                        return $layanan && stripos(strtolower($layanan->nama_layanan), $search) !== false;
                    }
                    return false;
                });

                return $customerMatch || $kucingMatch || $layananMatch;
            })->values();

            // Paginate hasil filter
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $bookings = new \Illuminate\Pagination\LengthAwarePaginator(
                $filtered->forPage($currentPage, $perPage),
                $filtered->count(),
                $perPage,
                $currentPage,
                ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
            );
        } else {
            // Jika tidak search, pakai paginate biasa
            $bookings = $query->paginate($perPage);
        }

        // Query untuk kalender: cek apakah filter_calendar dicentang
        if ($request->has('filter_calendar') && $request->input('filter_calendar')) {
            $calendarBookings = $bookings;
        } else {
            $calendarBookings = Booking::with(['kucings', 'tim', 'customer'])->get();
        }

        $events = [];
        foreach ($calendarBookings as $booking) {
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

        return view('admin.booking.index', [
            'events' => $events,
            'bookings' => $bookings,
        ]);
    } 

    public function byDate($tanggal)
    {
        $bookings = Booking::with(['customer', 'kucings', 'layanan'])
            ->whereDate('tanggalBooking', $tanggal)
            ->get();

        return view('admin.booking.list_by_date', compact('bookings', 'tanggal'));
    }

    public function show($id)
    {
        $booking = Booking::with(['customer', 'kucings'])->findOrFail($id);
        return view('admin.booking.show', compact('booking'));
    }

    public function acc($id, Request $request)
    {
        $booking = \App\Models\Booking::with(['customer', 'kucings'])->findOrFail($id);
        $timList = \App\Models\TimGroomer::all();

        $start = \Carbon\Carbon::parse($booking->tanggalBooking . ' ' . $booking->jamBooking);
        $end = $start->copy()->addMinutes($booking->estimasi ?? 90);

        $busyBookings = \App\Models\Booking::where('id', '!=', $booking->id)
            ->where('tanggalBooking', $booking->tanggalBooking)
            ->where('statusBooking', 'Proses')
            ->get()
            ->filter(function($b) use ($start, $end) {
                $otherStart = \Carbon\Carbon::parse($b->tanggalBooking . ' ' . $b->jamBooking);
                $otherEnd = $otherStart->copy()->addMinutes($b->estimasi ?? 90);
                return $start < $otherEnd && $end > $otherStart;
            });

        $busyTimIds = $busyBookings->pluck('tim_id')->unique();

        if ($request->hide_busy) {
            $timList = $timList->whereNotIn('id_tim', $busyTimIds);
        }

        return view('admin.booking.acc', compact('booking', 'timList', 'busyTimIds', 'busyBookings'));
    }

    public function accProses(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Hitung waktu mulai dan selesai booking yang akan di-ACC
        $start = \Carbon\Carbon::parse($booking->tanggalBooking . ' ' . $booking->jamBooking);
        $end = $start->copy()->addMinutes($booking->estimasi ?? 90);

        // Cari booking lain di tanggal yang sama, status 'Proses', dan tim yang sama
        $bentrok = Booking::where('id', '!=', $booking->id)
            ->where('tanggalBooking', $booking->tanggalBooking)
            ->where('tim_id', $request->tim_id)
            ->where('statusBooking', 'Proses')
            ->get()
            ->filter(function($b) use ($start, $end) {
                $otherStart = \Carbon\Carbon::parse($b->tanggalBooking . ' ' . $b->jamBooking);
                $otherEnd = $otherStart->copy()->addMinutes($b->estimasi ?? 90);
                // Cek overlap waktu
                return $start < $otherEnd && $end > $otherStart;
            });

        if ($bentrok->count() > 0) {
            return back()->withErrors(['tim_id' => 'Tim ini sudah ada jadwal grooming di jam tersebut!'])->withInput();
        }

        $booking->statusBooking = 'Proses';
        $booking->tim_id = $request->tim_id;
        $booking->save();

        return redirect()->route('admin.booking.by-date', ['tanggal' => $booking->tanggalBooking])
            ->with('success', 'Booking di-ACC dan tim telah dipilih!');
    }

    public function selesai($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->statusBooking = 'Selesai';
        $booking->save();
        return back()->with('success', 'Status booking diubah menjadi selesai.');
    }

    public function batalAcc($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->statusBooking = 'Pending';
        $booking->tim_id = null;
        $booking->save();
        return back()->with('success', 'ACC dibatalkan, status booking kembali ke pending.');
    }

    public function showSelesaiForm($id)
    {
        $booking = Booking::with(['kucings'])->findOrFail($id);
        return view('admin.booking.selesai', compact('booking'));
    }

    public function konfirmasiSelesai(Request $request, $id)
    {
        $booking = Booking::with(['kucings'])->findOrFail($id);

        // Simpan catatan/overview tiap kucing
        \Log::info($request->all());
        foreach ($booking->kucings as $kucing) {
            $catatan = $request->input('catatan_' . $kucing->pivot->kucing_id);
            $booking->kucings()->updateExistingPivot($kucing->pivot->kucing_id, [
                'catatan' => $catatan
            ]);
        }

        $booking->statusBooking = 'Selesai';
        $booking->save();

        return redirect()->route('admin.booking.by-date', ['tanggal' => $booking->tanggalBooking])
            ->with('success', 'Booking selesai dan catatan grooming telah disimpan.');
    }

    public function destroy(Booking $booking)
    {
        $tanggal = $booking->tanggalBooking;
        $booking->delete();
        return redirect()->route('admin.booking.by-date', ['tanggal' => $tanggal])
            ->with('success', 'Booking berhasil dihapus.');
    }

    public function edit(Booking $booking)
    {
        // Tambahkan validasi ini di awal method
        if ($booking->statusBooking !== 'Pending') {
            return redirect()->route('admin.bookings')->with('error', 'Booking hanya bisa diedit jika status Pending.');
        }

        $customers = \App\Models\Customer::all();
        $timList = \App\Models\TimGroomer::all();
        $layanans = \App\Models\Layanan::all();

        // Ambil semua kucing milik customer booking ini
        $allKucings = \App\Models\Kucing::where('customer_id', $booking->customer_id)->get();

        return view('admin.booking.edit', compact('booking', 'customers', 'timList', 'layanans', 'allKucings'));
    }

    public function update(Request $request, Booking $booking)
    {
        $kucings = $request->input('kucings', []);

        $validKucings = array_filter($kucings, function($kucing) {
            return !empty($kucing['id']) && !empty($kucing['layanan_id']);
        });

        if (count($validKucings) < 1) {
            return back()->withErrors(['kucings' => 'Minimal harus pilih satu kucing!'])->withInput();
        }

        // Hitung estimasi total dari layanan per kucing
        $totalEstimasi = 0;
        foreach ($validKucings as $kucing) {
            $layanan = \App\Models\Layanan::find($kucing['layanan_id']);
            if ($layanan) {
                $totalEstimasi += (int) $layanan->estimasi_pengerjaan_per_kucing;
            }
        }

        // Validasi bentrok tim
        $tanggal = $request->tanggalBooking;
        $jamMulai = \Carbon\Carbon::parse($tanggal . ' ' . $request->jamBooking);
        $jamSelesai = $jamMulai->copy()->addMinutes($totalEstimasi);

        $jumlahTim = \App\Models\TimGroomer::count();

        $bookingBentrok = Booking::whereDate('tanggalBooking', $tanggal)
            ->where('id', '!=', $booking->id) // exclude current booking
            ->where(function($q) use ($jamMulai, $jamSelesai) {
                $q->where(function($query) use ($jamMulai, $jamSelesai) {
                    $query->where('jamBooking', '<', $jamSelesai->format('H:i'))
                        ->whereRaw("ADDTIME(jamBooking, SEC_TO_TIME(estimasi*60)) > ?", [$jamMulai->format('H:i')]);
                });
            })
            ->count();

        if ($bookingBentrok >= $jumlahTim) {
            return back()->withErrors([
                'jamBooking' => 'Bentrok dengan jadwal lain dan tim yang tersedia sudah ditugaskan semua pada jam tersebut, silahkan pilih jam atau tanggal lain.'
            ])->withInput();
        }

        // Update data utama booking (termasuk estimasi)
        $booking->update([
            'tanggalBooking' => $request->tanggalBooking,
            'jamBooking'     => $request->jamBooking,
            'alamatBooking'  => $request->alamatBooking,
            'estimasi'       => $totalEstimasi,
        ]);

        // Update relasi kucing & layanan di pivot
        $syncData = [];
        foreach ($validKucings as $kucing) {
            $syncData[$kucing['id']] = [
                'layanan_id' => $kucing['layanan_id']
            ];
        }
        $booking->kucings()->sync($syncData);

        return redirect()->route('admin.bookings')->with('success', 'Booking berhasil diupdate.');
    }

    public function deleteDisabledDate($id)
    {
        \App\Models\DisabledDate::findOrFail($id)->delete();
        return back()->with('success', 'Tanggal berhasil dihapus dari daftar nonaktif.');
    }

    public function disabledDates()
    {
        $disabledDates = \App\Models\DisabledDate::orderBy('tanggal', 'asc')->paginate(10);
        return view('admin.booking.disabled_dates', compact('disabledDates'));
    }

    public function storeDisabledDate(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'keterangan' => 'nullable|string|max:255'
        ], [
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini.',
        ]);

        // Cek apakah tanggal sudah ada
        $exists = \App\Models\DisabledDate::where('tanggal', $request->tanggal)->exists();
        if ($exists) {
            return back()->withErrors(['tanggal' => 'Tanggal ini sudah dinonaktifkan.'])->withInput();
        }

        \App\Models\DisabledDate::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return back()->with('success', 'Tanggal berhasil dinonaktifkan.');
    }
}
