<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('admin.profile.show', compact('user'));
    }
    
    public function edit()
    {
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.profile.show')->with('success', 'Profil berhasil diperbarui');
    }
    
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'password.min' => 'Password baru minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);
        
        if (!Hash::check($validated['current_password'], auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak benar']);
        }
        
        auth()->user()->update([
            'password' => Hash::make($validated['password'])
        ]);
        
        return redirect()->route('admin.profile.show')->with('success', 'Password berhasil diubah');
    }
}