<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimGroomer;
use App\Models\Groomer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        // Ambil semua id groomer yang sudah menjadi anggota di tim manapun
        $usedGroomerIds = TimGroomer::pluck('anggota_1')
            ->merge(TimGroomer::pluck('anggota_2'))
            ->filter()
            ->unique()
            ->toArray();

        return view('admin.tim_groomer.create', compact('groomers', 'usedGroomerIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tim'  => 'required|string|max:100',
            'anggota_1' => [
                'required',
                'different:anggota_2',
                'exists:groomers,id_groomer',
            ],
            'anggota_2' => [
                'nullable',
                'different:anggota_1',
                'exists:groomers,id_groomer',
            ],
        ]);

        // Pindahkan anggota jika sudah ada di tim lain
        foreach (['anggota_1', 'anggota_2'] as $anggota) {
            $id = $request->$anggota;
            if ($id) {
                $timLama = \App\Models\TimGroomer::where('anggota_1', $id)
                    ->orWhere('anggota_2', $id)
                    ->first();
                if ($timLama) {
                    if ($timLama->anggota_1 == $id) {
                        $timLama->anggota_1 = null;
                    }
                    if ($timLama->anggota_2 == $id) {
                        $timLama->anggota_2 = null;
                    }
                    $timLama->save();
                }
            }
        }

        TimGroomer::create($request->only('nama_tim', 'anggota_1', 'anggota_2'));
        return redirect()->route('admin.tim-groomer.index')->with('success', 'Tim berhasil ditambahkan');
    }

    public function edit(TimGroomer $tim_groomer)
    {
        $groomers = Groomer::all();

        

        // Ambil semua id groomer yang sudah menjadi anggota di tim lain (kecuali tim ini)
        $usedGroomerMap = TimGroomer::where('id_tim', '!=', $tim_groomer->id_tim)
            ->get()
            ->reduce(function($carry, $tim) {
                if ($tim->anggota_1) $carry[(string)$tim->anggota_1] = $tim->nama_tim;
                if ($tim->anggota_2) $carry[(string)$tim->anggota_2] = $tim->nama_tim;
                return $carry;
            }, []);
        

        // Hapus anggota yang sudah menjadi anggota di tim ini (agar tidak muncul label "Sudah di Tim ...")
        unset($usedGroomerMap[(string)$tim_groomer->anggota_1]);
        unset($usedGroomerMap[(string)$tim_groomer->anggota_2]);
        return view('admin.tim_groomer.edit', compact('tim_groomer', 'groomers', 'usedGroomerMap'));
    }

    public function update(Request $request, TimGroomer $tim_groomer)
    {
        $request->validate([
            'nama_tim'  => 'required|string|max:100',
            'anggota_1' => [
                'required',
                'different:anggota_2',
                'exists:groomers,id_groomer',
            ],
            'anggota_2' => [
                'nullable',
                'different:anggota_1',
                'exists:groomers,id_groomer',
            ],
        ]);

        // Pindahkan anggota jika sudah ada di tim lain (kecuali tim ini)
        foreach (['anggota_1', 'anggota_2'] as $anggota) {
            $id = $request->$anggota;
            if ($id) {
                $timLama = \App\Models\TimGroomer::where('id_tim', '!=', $tim_groomer->id_tim)
                    ->where(function($q) use ($id) {
                        $q->where('anggota_1', $id)
                        ->orWhere('anggota_2', $id);
                    })
                    ->first();
                if ($timLama) {
                    if ($timLama->anggota_1 == $id) {
                        $timLama->anggota_1 = null;
                    }
                    if ($timLama->anggota_2 == $id) {
                        $timLama->anggota_2 = null;
                    }
                    $timLama->save();
                }
            }
        }

        $tim_groomer->update($request->only('nama_tim', 'anggota_1', 'anggota_2'));
        return redirect()->route('admin.tim-groomer.index')->with('success', 'Tim berhasil diupdate');
    }

    public function destroy(TimGroomer $tim_groomer)
    {
        $tim_groomer->delete();
        return redirect()->route('admin.tim-groomer.index')->with('success', 'Tim berhasil dihapus');
    }
}