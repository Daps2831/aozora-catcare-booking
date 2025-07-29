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
        $groomer->delete();
        return redirect()->route('admin.groomer.index')->with('success', 'Groomer berhasil dihapus');
    }

}
