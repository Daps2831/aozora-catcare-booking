<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimGroomer;
use App\Models\Groomer;
use Illuminate\Http\Request;

class TimGroomerController extends Controller
{
    public function index()
    {
        $tim = TimGroomer::with(['anggota1', 'anggota2'])->get();
        return view('admin.tim_groomer.index', compact('tim'));
    }

    public function create()
    {
        $groomers = Groomer::all();
        return view('admin.tim_groomer.create', compact('groomers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_1' => 'required|different:anggota_2|exists:groomers,id_groomer',
            'anggota_2' => 'nullable|different:anggota_1|exists:groomers,id_groomer',
        ]);
        TimGroomer::create($request->only('anggota_1', 'anggota_2'));
        return redirect()->route('admin.tim-groomer.index')->with('success', 'Tim berhasil ditambahkan');
    }

    public function edit(TimGroomer $tim_groomer)
    {
        $groomers = Groomer::all();
        return view('admin.tim_groomer.edit', compact('tim_groomer', 'groomers'));
    }

    public function update(Request $request, TimGroomer $tim_groomer)
    {
        $request->validate([
            'anggota_1' => 'required|different:anggota_2|exists:groomers,id_groomer',
            'anggota_2' => 'nullable|different:anggota_1|exists:groomers,id_groomer',
        ]);
        $tim_groomer->update($request->only('anggota_1', 'anggota_2'));
        return redirect()->route('admin.tim-groomer.index')->with('success', 'Tim berhasil diupdate');
    }

    public function destroy(TimGroomer $tim_groomer)
    {
        $tim_groomer->delete();
        return redirect()->route('admin.tim-groomer.index')->with('success', 'Tim berhasil dihapus');
    }
}