<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kucing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk mengelola file

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
        // Validasi data form, termasuk gambar
        $validatedData = $request->validate([
            'nama_kucing' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'umur' => 'required|integer',
            'riwayat_kesehatan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk gambar
        ]);

        // Tambahkan customer_id ke data yang divalidasi
        $validatedData['customer_id'] = Auth::user()->customer->id;

        // Cek jika ada file gambar yang di-upload
        if ($request->hasFile('gambar')) {
            // Simpan gambar ke storage/app/public/kucing_images
            // dan simpan path-nya ke dalam data yang akan disimpan
            $path = $request->file('gambar')->store('kucing_images', 'public');
            $validatedData['gambar'] = $path;
        }

        // Buat entri kucing baru dengan semua data
        Kucing::create($validatedData);

        return redirect()->route('kucing.register')->with('success', 'Data kucing berhasil disimpan!');
    } 

     // Method untuk menampilkan halaman edit
    public function edit(Kucing $kucing)
    {
        // Pastikan kucing ini milik user yang sedang login
        if ($kucing->customer_id != Auth::user()->customer->id) {
            abort(403, 'Akses Ditolak');
        }
        
        return view('kucing.edit', compact('kucing'));
    }

    // Method untuk memperbarui data kucing
    public function update(Request $request, Kucing $kucing)
    {
        // Pastikan kucing ini milik user yang sedang login
        if ($kucing->customer_id != Auth::user()->customer->id) {
            abort(403, 'Akses Ditolak');
        }

        // Validasi input
        $validatedData = $request->validate([
            'nama_kucing' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'umur' => 'required|integer',
            'riwayat_kesehatan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk gambar
        ]);

        // Logika untuk upload dan update gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($kucing->gambar) {
                Storage::delete('public/' . $kucing->gambar);
            }
            // Simpan gambar baru dan dapatkan path-nya
            $path = $request->file('gambar')->store('kucing_images', 'public');
            $validatedData['gambar'] = $path;
        }

        // Update data kucing di database
        $kucing->update($validatedData);

        return redirect()->route('user.dashboard')->with('success', 'Data kucing berhasil diperbarui!');
    }

    public function destroy(Kucing $kucing)
    {
        // Pastikan kucing ini milik user yang sedang login
        if ($kucing->customer_id != auth()->user()->customer->id) {
            abort(403, 'Akses Ditolak');
        }

        // Hapus gambar jika ada
        if ($kucing->gambar) {
            Storage::delete('public/' . $kucing->gambar);
        }

        $kucing->delete();

        return redirect()->route('user.dashboard')->with('success', 'Data kucing berhasil dihapus!');
    }
}
