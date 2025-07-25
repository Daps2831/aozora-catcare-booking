<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Menampilkan halaman profil user
    public function show()
    {
        $user = Auth::user();  // Ambil data user yang sedang login
        return view('customer.data', compact('user'));  // Tampilkan view profil dengan data user
    }

    // Menampilkan halaman untuk edit profil
    public function edit()
    {
        $user = Auth::user();  // Ambil data user yang sedang login
        return view('customer.data', compact('user'))->with('edit', true);  // Tampilkan halaman edit
    }

    // Menyimpan perubahan profil
    public function update(Request $request)
    {
        $user = Auth::user();  // Ambil data user yang sedang login

        // Validasi data input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kontak' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        // Update data user
        $user->update([
            'name' => $validated['name'],
            'kontak' => $validated['kontak'],
            'alamat' => $validated['alamat'],
        ]);

        // Update tabel customers (jika relasi ada)
        if ($user->customer) {
            $user->customer->update([
                'name' => $validated['name'],
                'kontak' => $validated['kontak'],
                'alamat' => $validated['alamat'],
            ]);
        }

        // Redirect ke halaman profil dengan pesan sukses
        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    public function riwayat()
    {
        $riwayatBookings = auth()->user()->bookings()->with(['layanan', 'kucings'])->latest()->get();
        return view('customer.riwayat', compact('riwayatBookings'));
    }
}
