<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'kucings', 'layanan'])->get();

        $events = $bookings->map(function($b) {
            return [
                'id'    => $b->id,
                'title' => $b->customer->name . ' - ' . $b->kucings->pluck('nama_kucing')->join(', '),
                'start' => $b->tanggal . 'T' . $b->jam,
                'color' => $b->status == 'Selesai' ? '#28a745' : ($b->status == 'Batal' ? '#dc3545' : '#ffc107'),
            ];
        });

        return view('admin.booking.index', [
            'events' => $events,
            // ...tambahkan data lain jika perlu
        ]);
    }
}
