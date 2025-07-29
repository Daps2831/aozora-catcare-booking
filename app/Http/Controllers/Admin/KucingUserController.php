<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Kucing;
use Illuminate\Http\Request;

class KucingUserController extends Controller
{
    public function create($userId)
    {
        $user = User::findOrFail($userId);
        $customer = $user->customer;
        return view('admin.kucing.create', compact('user', 'customer'));
    }

    public function store(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $customer = $user->customer;
        $validated = $request->validate([
            'nama_kucing' => 'required',
            'jenis' => 'required',
            'umur' => 'required|numeric',
        ]);
        $validated['customer_id'] = $customer->id;
        Kucing::create($validated);
        return redirect()->route('admin.users.show', $user->id)->with('success', 'Kucing berhasil ditambahkan');
    }

    public function edit($userId, $kucingId)
    {
        $user = User::findOrFail($userId);
        $customer = $user->customer;
        $kucing = Kucing::findOrFail($kucingId);
        return view('admin.kucing.edit', compact('user', 'customer', 'kucing'));
    }

    public function update(Request $request, $userId, $kucingId)
    {
        $kucing = Kucing::findOrFail($kucingId);
        $validated = $request->validate([
            'nama_kucing' => 'required',
            'jenis' => 'required',
            'umur' => 'required|numeric',
        ]);
        $kucing->update($validated);
        return redirect()->route('admin.users.show', $userId)->with('success', 'Kucing berhasil diupdate');
    }

    public function destroy($userId, $kucingId)
    {
        $kucing = Kucing::findOrFail($kucingId);
        $kucing->delete();
        return redirect()->route('admin.users.show', $userId)->with('success', 'Kucing berhasil dihapus');
    }
}