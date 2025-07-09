<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Untuk halaman dashboard admin
    public function adminDashboard()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();
        
        // Tampilkan halaman dashboard admin
        return view('admin.dashboard', compact('user'));
    }

    // Untuk halaman dashboard user biasa
    public function userDashboard()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();
        
        // Tampilkan halaman dashboard user
        return view('user.dashboard', compact('user'));
    }
}

