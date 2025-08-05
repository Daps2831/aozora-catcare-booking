<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Kucing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KucingUserController extends Controller
{
    public function create($userId)
    {
        $user = User::findOrFail($userId);
        
        // Pastikan user memiliki customer profile
        if (!$user->customer) {
            return redirect()->route('admin.users.show', $user->id)
                ->with('error', 'User belum memiliki profil customer. Silakan lengkapi profil customer terlebih dahulu.');
        }
        
        return view('admin.kucing.create', compact('user'));
    }

    public function store(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        // Pastikan user memiliki customer profile
        if (!$user->customer) {
            return redirect()->route('admin.users.show', $user->id)
                ->with('error', 'User belum memiliki profil customer.');
        }

        $validated = $request->validate([
            'nama_kucing' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'umur' => 'required|integer|min:0|max:30',
            'riwayat_kesehatan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // Max 2MB
        ], [
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus JPEG, JPG, atau PNG',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle file upload
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('kucing_images', 'public');
        }

        // Create kucing - gunakan customer_id dari user yang dipilih, bukan admin yang login
        Kucing::create([
            'customer_id' => $user->customer->id,  // Gunakan customer dari user yang dipilih
            'nama_kucing' => $validated['nama_kucing'],
            'jenis' => $validated['jenis'],
            'umur' => $validated['umur'],
            'riwayat_kesehatan' => $validated['riwayat_kesehatan'],
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Data kucing berhasil ditambahkan untuk ' . $user->name . '!');
    }

    public function edit($userId, $kucingId)
    {
        $user = User::findOrFail($userId);
        $kucing = Kucing::findOrFail($kucingId);
        
        // Pastikan kucing milik customer ini
        if ($kucing->customer_id !== $user->customer->id) {
            return redirect()->route('admin.users.show', $user->id)
                ->with('error', 'Kucing tidak ditemukan.');
        }
        
        return view('admin.kucing.edit', compact('user', 'kucing'));
    }

    public function update(Request $request, $userId, $kucingId)
    {
        $user = User::findOrFail($userId);
        $kucing = Kucing::findOrFail($kucingId);
        
        // Pastikan kucing milik customer ini
        if ($kucing->customer_id !== $user->customer->id) {
            return redirect()->route('admin.users.show', $user->id)
                ->with('error', 'Kucing tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama_kucing' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'umur' => 'required|integer|min:0|max:30',
            'riwayat_kesehatan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus JPEG, JPG, atau PNG',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($kucing->gambar && Storage::disk('public')->exists($kucing->gambar)) {
                Storage::disk('public')->delete($kucing->gambar);
            }
            
            $validated['gambar'] = $request->file('gambar')->store('kucing_images', 'public');
        }

        $kucing->update($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Data kucing berhasil diupdate untuk ' . $user->name . '!');
    }

    public function destroy($userId, $kucingId)
    {
        $user = User::findOrFail($userId);
        $kucing = Kucing::findOrFail($kucingId);
        
        // Pastikan kucing milik customer ini
        if ($kucing->customer_id !== $user->customer->id) {
            return redirect()->route('admin.users.show', $user->id)
                ->with('error', 'Kucing tidak ditemukan.');
        }
        
        // Delete image if exists
        if ($kucing->gambar && Storage::disk('public')->exists($kucing->gambar)) {
            Storage::disk('public')->delete($kucing->gambar);
        }
        
        $kucing->delete();
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Data kucing berhasil dihapus!');
    }
}