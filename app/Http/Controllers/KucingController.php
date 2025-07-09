<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kucing;
use Illuminate\Support\Facades\Auth;

class KucingController extends Controller
{
    // Menampilkan form pendaftaran kucing
    public function showForm()
    {
        return view('kucing.register');
    }

    // Menyimpan data kucing ke dalam database
    public function store(Request $request)
    {
        // Validasi data form
        $request->validate([
            'namaKucing' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'umur' => 'required|integer',
            'riwayatKesehatan' => 'nullable|string',
        ]);

        // Menyimpan data kucing ke dalam database
        Kucing::create([
            'customerId' => Auth::id(),  // Mengambil ID customer yang sedang login
            'namaKucing' => $request->namaKucing,
            'jenis' => $request->jenis,
            'umur' => $request->umur,
            'riwayatKesehatan' => $request->riwayatKesehatan,
        ]);

        // Redirect setelah sukses
        return redirect()->route('kucing.register')->with('success', 'Data kucing berhasil disimpan!');
    }
}
