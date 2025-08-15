<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kucing;
use App\Models\Booking;

class DashboardController extends Controller
{
  


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