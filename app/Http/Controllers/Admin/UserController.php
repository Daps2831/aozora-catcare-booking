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
            'role' => 'required|in:user,customer', // Hanya user dan customer yang diizinkan
            // Validasi data customer (optional)
            'customer_name' => 'nullable|string|max:255',
            'customer_username' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_kontak' => 'nullable|string|max:20',
            'customer_alamat' => 'nullable|string',
        ], [
            'role.in' => 'Role yang dipilih tidak valid. Hanya User dan Customer yang diizinkan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'username.unique' => 'Username sudah digunakan, pilih username lain.',
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
        
        // Buat customer profile jika ada data customer yang diisi
        if ($validated['role'] !== 'admin' && 
            ($validated['customer_name'] || $validated['customer_username'] || 
            $validated['customer_email'] || $validated['customer_kontak'] || 
            $validated['customer_alamat'])) {
            
            Customer::create([
                'user_id' => $user->id,
                'name' => $validated['customer_name'],
                'username' => $validated['customer_username'],
                'email' => $validated['customer_email'],
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
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            // Validasi data customer
            'customer_name' => 'nullable|string|max:255',
            'customer_username' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_kontak' => 'nullable|string|max:20',
            'customer_alamat' => 'nullable|string',
        ], [
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);
        
        // Update data user
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        
        // Update password jika diisi
        if (!empty($validated['password'])) {
            $userData['password'] = bcrypt($validated['password']);
        }
        
        $user->update($userData);
        
        // Update atau create data customer untuk semua user kecuali admin
        if ($user->role !== 'admin') {
            $customerData = [
                'name' => $validated['customer_name'],
                'username' => $validated['customer_username'],
                'email' => $validated['customer_email'],
                'kontak' => $validated['customer_kontak'],
                'alamat' => $validated['customer_alamat'],
            ];
            
            if ($user->customer) {
                // Update existing customer
                $user->customer->update($customerData);
            } else {
                // Create new customer profile
                $customerData['user_id'] = $user->id;
                Customer::create($customerData);
            }
        }
        
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
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
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}