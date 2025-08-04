<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Groomer;

class GroomerController extends Controller
{
    public function index()
    {
        $groomers = Groomer::all();
        return view('admin.groomer.index', compact('groomers'));
    }

    public function show(Groomer $groomer)
    {
        return view('admin.groomer.show', compact('groomer'));
    }

    public function create()
    {
        return view('admin.groomer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
        ]);
        Groomer::create($validated);
        return redirect()->route('admin.groomer.index')->with('success', 'Groomer berhasil ditambahkan');
    }

    public function edit(Groomer $groomer)
    {
        return view('admin.groomer.edit', compact('groomer'));
    }

    public function update(Request $request, Groomer $groomer)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
        ]);
        $groomer->update($validated);
        return redirect()->route('admin.groomer.index')->with('success', 'Groomer berhasil diupdate');
    }

    public function destroy(Groomer $groomer)
    {
        // Cari tim di mana groomer ini adalah anggota 1
        $tim = \App\Models\TimGroomer::where('anggota_1', $groomer->id_groomer)->first();

        if ($tim) {
            // Cek apakah tim ini sedang dipakai di booking yang belum selesai
            $bookingBentrok = \App\Models\Booking::where('tim_id', $tim->id_tim)
                ->where('statusBooking', '!=', 'Selesai')
                ->exists();

            if ($bookingBentrok) {
                return redirect()->route('admin.groomer.index')->with('error', 'Tidak bisa menghapus groomer karena masih menjadi anggota 1 di tim yang sedang menangani booking bentrok.');
            }
        }

        try {
            $groomer->delete();
            return redirect()->route('admin.groomer.index')->with('success', 'Groomer berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.groomer.index')->with('error', 'Gagal menghapus groomer.');
        }
    }

}
