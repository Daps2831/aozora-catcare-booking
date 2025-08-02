<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\TimGroomer;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['kucings', 'tim'])->get();

        $events = [];
        foreach ($bookings as $booking) {
            $start = \Carbon\Carbon::parse($booking->tanggalBooking . ' ' . $booking->jamBooking);
            $end = $start->copy()->addMinutes($booking->estimasi ?? 90);

            $events[] = [
                'title' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                'start' => $start->toDateTimeString(),
                'end'   => $end->toDateTimeString(),
                'jumlahKucing' => $booking->kucings->count(),
                'namaTim'      => $booking->tim ? $booking->tim->nama_tim : '-',
            ];
        }

        return view('admin.booking.index', [
            'events' => $events,
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
}
