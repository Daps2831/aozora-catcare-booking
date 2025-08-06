<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('customer')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:user,customer',
            // Validasi data customer (optional) - HAPUS customer_username dari validasi
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_kontak' => 'nullable|string|max:20',
            'customer_alamat' => 'nullable|string',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
            'role.in' => 'Role yang dipilih tidak valid. Hanya User dan Customer yang diizinkan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);
        
        // Proteksi tambahan: Pastikan tidak ada yang mencoba membuat admin
        if ($validated['role'] === 'admin') {
            return back()->withErrors(['role' => 'Role Admin tidak dapat dibuat melalui form ini.'])->withInput();
        }
        
        // Buat user baru
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);
        
        // Buat customer profile - username SELALU sama dengan user username
        if ($validated['role'] !== 'admin') {
            Customer::create([
                'user_id' => $user->id,
                'name' => $validated['customer_name'] ?? $validated['name'],
                'username' => $validated['username'], // PAKSA sama dengan username user
                'email' => $validated['customer_email'] ?? $validated['email'],
                'kontak' => $validated['customer_kontak'],
                'alamat' => $validated['customer_alamat'],
            ]);
        }
        
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['customer', 'customer.kucings'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        
        // Proteksi: Admin tidak bisa edit user admin lain
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'User dengan role Admin tidak dapat diedit.');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        
        // Proteksi: Admin tidak bisa update user admin lain
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'User dengan role Admin tidak dapat diupdate.');
        }
        
        // Validasi data user
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            // Validasi data customer (HAPUS customer_username dari validasi)
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_kontak' => 'nullable|string|max:20',
            'customer_alamat' => 'nullable|string',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);
        
        // Simpan username lama untuk perbandingan
        $oldUsername = $user->username;
        
        // Update data user
        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
        ]);
        
        // Update atau buat customer profile
        if ($user->customer) {
            // Update customer yang sudah ada
            $user->customer->update([
                'name' => $validated['customer_name'] ?? $validated['name'],
                'username' => $validated['username'], // OTOMATIS SYNC dengan username user
                'email' => $validated['customer_email'] ?? $validated['email'],
                'kontak' => $validated['customer_kontak'],
                'alamat' => $validated['customer_alamat'],
            ]);
        } else {
            // Buat customer baru jika belum ada
            Customer::create([
                'user_id' => $user->id,
                'name' => $validated['customer_name'] ?? $validated['name'],
                'username' => $validated['username'], // OTOMATIS SYNC dengan username user
                'email' => $validated['customer_email'] ?? $validated['email'],
                'kontak' => $validated['customer_kontak'],
                'alamat' => $validated['customer_alamat'],
            ]);
        }
        
        // Pesan sukses dengan info username sync
        $message = 'User berhasil diupdate';
        if ($oldUsername !== $validated['username']) {
            $message .= '. Username customer otomatis berubah dari "' . $oldUsername . '" menjadi "' . $validated['username'] . '"';
        }
        
        return redirect()->route('admin.users.show', $user->id)->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Proteksi: Admin tidak bisa hapus user admin lain
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'User dengan role Admin tidak dapat dihapus.');
        }
        
        // Proteksi: Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        
        // Simpan nama user untuk pesan konfirmasi
        $userName = $user->name;
        $userEmail = $user->email;
        $userUsername = $user->username ?? 'N/A';
        
        // Hapus user - cascade delete akan otomatis menghapus:
        // 1. Data customer terkait
        // 2. Semua data kucing terkait
        // 3. File gambar kucing (handled by boot method)
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userName}' (Email: {$userEmail}, Username: {$userUsername}) dan semua data terkaitnya berhasil dihapus. Email dan username dapat digunakan kembali.");
    }
}