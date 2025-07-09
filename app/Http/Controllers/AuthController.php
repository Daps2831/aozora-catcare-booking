<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer; 

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('login');
    }

    // Menangani login
    public function login(Request $request)
    {
        // Validasi login form
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Mencoba login dengan kredensial
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Ambil user yang sedang login
            $user = Auth::user();

            // Cek apakah role user adalah admin
            if ($user->role === 'admin') {
                // Jika admin, arahkan ke dashboard admin
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil sebagai Admin!');
            }

            // Jika user biasa, arahkan ke dashboard user
            return redirect()->route('user.dashboard')->with('success', 'Login berhasil sebagai User!');
        }

        // Jika gagal login, kembali ke form login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Menampilkan halaman registrasi
    public function showRegistrationForm()
    {
        return view('register');
    }

    // Menangani pendaftaran pengguna
    public function register(Request $request)
    {
        // Validasi form registrasi
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|max:255',  // Validasi username
            'email' => 'required|email|unique:users,email|max:255',  // Validasi email
            'password' => 'required|string|min:8|confirmed',  // Validasi password
            'name' => 'required|string|max:255',  // Validasi nama
            'kontak' => 'required|string|max:20',  // Validasi kontak
            'alamat' => 'required|string|max:255',  // Validasi alamat
    ]);

        // Membuat pengguna baru
          $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'name' => $validated['name'],
            'kontak' => $validated['kontak'],
            'alamat' => $validated['alamat'],
            
    ]);

          Customer::create([
            'user_id' => $user->id,  // Mengaitkan customer dengan user
            'name' => $validated['name'], // Menambahkan nama customer
            'username' => $validated['username'], // Misalnya, menambahkan usernama
            'kontak' => $validated['kontak'],  // Menyimpan kontak
            'alamat' => $validated['alamat'],  // Menyimpan alamat
            'email' => $validated['email'],  // Menyimpan email
        ]);

        // Redirect ke halaman login setelah pendaftaran
        return redirect()->route('login.form')->with('success', 'Akun berhasil dibuat.');
    }
}
