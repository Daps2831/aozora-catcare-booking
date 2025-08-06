<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Kucing;
use App\Models\Layanan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    // Statistik Booking
    $today = Carbon::today();
    $month = Carbon::now()->month;
    $year = Carbon::now()->year;

    $totalBookingHariIni = Booking::whereDate('created_at', $today)->count();
    $totalBookingBulanIni = Booking::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
    $totalBookingTahunIni = Booking::whereYear('created_at', $year)->count();

    $bookingPerStatus = Booking::selectRaw('statusBooking, COUNT(*) as total')
        ->groupBy('statusBooking')->pluck('total', 'statusBooking');

    // Grafik tren booking per hari (7,30 hari dan 1 tahun terakhir)
    $labels7 = [];
    $data7 = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = \Carbon\Carbon::today()->subDays($i);
        $labels7[] = $date->format('d M');
        $data7[] = Booking::whereDate('tanggalBooking', $date)->count(); // Ganti created_at menjadi tanggalBooking
    }

    $labels30 = [];
    $data30 = [];
    for ($i = 29; $i >= 0; $i--) {
        $date = \Carbon\Carbon::today()->subDays($i);
        $labels30[] = $date->format('d M');
        $data30[] = Booking::whereDate('tanggalBooking', $date)->count(); // Ganti created_at menjadi tanggalBooking
    }

    $labels365 = [];
    $data365 = [];
    for ($i = 364; $i >= 0; $i -= 30) {
        $date = \Carbon\Carbon::today()->subDays($i);
        $labels365[] = $date->format('M Y');
        $data365[] = Booking::whereMonth('tanggalBooking', $date->month)
                            ->whereYear('tanggalBooking', $date->year)->count(); // Ganti created_at menjadi tanggalBooking
    }

    // Tambahkan setelah perhitungan grafik tren booking
    $labels7Future = [];
    $data7Future = [];
    for ($i = 0; $i < 7; $i++) {
        $date = \Carbon\Carbon::today()->addDays($i);
        $labels7Future[] = $date->format('d M');
        $data7Future[] = Booking::whereDate('tanggalBooking', $date)->count();
    }

    $labels30Future = [];
    $data30Future = [];
    for ($i = 0; $i < 30; $i++) {
        $date = \Carbon\Carbon::today()->addDays($i);
        $labels30Future[] = $date->format('d M');
        $data30Future[] = Booking::whereDate('tanggalBooking', $date)->count();
    }

    $labels365Future = [];
    $data365Future = [];
    for ($i = 0; $i < 365; $i += 30) {
        $date = \Carbon\Carbon::today()->addDays($i);
        $labels365Future[] = $date->format('M Y');
        $data365Future[] = Booking::whereMonth('tanggalBooking', $date->month)
                                ->whereYear('tanggalBooking', $date->year)->count();
    }

    // Jam/jadwal paling ramai
    $jamRamai = Booking::selectRaw('jamBooking, COUNT(*) as total')
        ->groupBy('jamBooking')->orderByDesc('total')->first();

    // Statistik User
    $totalUser = User::count();
    $userBaruBulanIni = User::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
    $userAktifBulanIni = User::whereHas('bookings', function($q) use ($month, $year) {
        $q->whereMonth('created_at', $month)->whereYear('created_at', $year);
    })->count();

    // Statistik Kucing
    $totalKucing = Kucing::count();
    $jenisFavorit = Kucing::selectRaw('jenis, COUNT(*) as total')
        ->groupBy('jenis')->orderByDesc('total')->first();

    // Statistik Layanan
    $layananPopuler = Layanan::withCount('bookings')->orderByDesc('bookings_count')->first();
    $bookingPerLayanan = Layanan::withCount('bookings')->get();
    
    // Pendapatan (jika ada kolom harga di layanan)
    $totalPendapatan = 0;
    $pendapatanPerLayanan = [];
    foreach ($bookingPerLayanan as $layanan) {
        $harga = $layanan->harga ?? 0;
        $jumlah = $layanan->bookings_count; // GANTI INI
        $pendapatanPerLayanan[$layanan->nama_layanan] = $harga * $jumlah;
        $totalPendapatan += $harga * $jumlah;
    }

    return view('admin.dashboard', compact(
        'totalBookingHariIni', 'totalBookingBulanIni', 'totalBookingTahunIni',
        'bookingPerStatus', 'labels7', 'data7', 'labels30', 'data30', 'labels365', 'data365', 'jamRamai',
        'totalUser', 'userBaruBulanIni', 'userAktifBulanIni',
        'totalKucing', 'jenisFavorit',
        'layananPopuler', 'bookingPerLayanan',
        'totalPendapatan', 'pendapatanPerLayanan',
        'labels7Future', 'data7Future', 'labels30Future', 'data30Future', 'labels365Future', 'data365Future'
    ));
}
}
