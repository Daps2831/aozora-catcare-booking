<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kucing;
use App\Models\Booking;

class DashboardController extends Controller
{
    // Untuk halaman dashboard admin
    public function adminDashboard()
    {
        $user = Auth::user();
        
        // Data untuk Kartu Statistik
        $jumlahPelanggan = User::where('role', 'user')->count();
        $jumlahKucing = Kucing::count();
        $bookingHariIni = Booking::whereDate('tanggalBooking', today())->count();

        // Data untuk Tabel
        $jadwalTerbaru = Booking::with(['customer', 'kucing', 'layanan'])->latest()->take(5)->get();
        $pelangganBaru = User::where('role', 'user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('user', 'jumlahPelanggan', 'jumlahKucing', 'bookingHariIni', 'jadwalTerbaru', 'pelangganBaru'));
    }

    // Untuk halaman dashboard user biasa
    public function userDashboard()
    {
        $user = Auth::user();

        // Ambil data kucing dan jadwal booking milik user yang sedang login
        // Ganti 'customerId' menjadi 'customer_id'
        $kucingPengguna = Kucing::where('customer_id', $user->customer->id)->get();
        $jadwalPengguna = Booking::where('customer_id', $user->customer->id)
                                ->where('statusBooking', '!=', 'Selesai')
                                ->with(['kucings', 'layanan'])
                                ->get();
        
        return view('user.dashboard', compact('user', 'kucingPengguna', 'jadwalPengguna'));
    }
}