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

        // Ambil data kucing milik user
        $kucingPengguna = Kucing::where('customer_id', $user->customer->id)->get();
        
        // Ambil semua booking (termasuk yang masa depan dan yang batal)
        $jadwalPengguna = Booking::where('customer_id', $user->customer->id)
                                ->with(['kucings', 'tim'])
                                ->orderBy('tanggalBooking', 'desc')
                                ->orderBy('jamBooking', 'desc')
                                ->get();
        
        return view('user.dashboard', compact('user', 'kucingPengguna', 'jadwalPengguna'));
    }
}