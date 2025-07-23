@extends('layouts.app')

@section('title', 'Profil User')

@section('content')
    <h2>Profil User: {{ $user->name }}</h2>

    <!-- Menampilkan data profil pengguna -->
    <div class="profile-details">
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Kontak:</strong> {{ $user->kontak }}</p>
        <p><strong>Alamat:</strong> {{ $user->alamat }}</p>
    </div>

    <!-- Tombol Edit untuk memperbarui profil -->
    <a href="{{ route('profile.edit') }}" class="btn-edit">Edit Profil</a>

    <!-- Jika edit profil, tampilkan form edit -->
    @if (isset($edit) && $edit)
        <div class="edit-profile">
            <h3>Edit Profil</h3>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')  <!-- Menandakan ini adalah request PUT untuk update -->

                <!-- Input untuk Nama -->
                <div>
                    <label for="name">Nama:</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}" required>
                </div>

                <!-- Input untuk Kontak -->
                <div>
                    <label for="kontak">Kontak:</label>
                    <input type="text" id="kontak" name="kontak" value="{{ $user->kontak }}" required>
                </div>

                <!-- Input untuk Alamat -->
                <div>
                    <label for="alamat">Alamat:</label>
                    <input type="text" id="alamat" name="alamat" value="{{ $user->alamat }}" required>
                </div>

                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </form>
        </div>
    @endif
@endsection


