@extends('layouts.app')

@section('title', 'Registrasi Akun')

@section('content')
    <section class="register-section">
        <div class="register-container">
            <h1>Registrasi Akun</h1>
            <p>Silakan isi formulir di bawah ini untuk membuat akun dan bergabung dengan layanan grooming kucing kami.</p>

           <!-- Menampilkan pesan kesalahan jika ada -->
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

            <!-- Form Registrasi -->
            <form action="{{ route('register.submit') }}" method="POST">
                @csrf  <!-- Token CSRF untuk keamanan -->

                  <!-- Name -->
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                  <!-- Kontak -->
                <div class="form-group">
                    <label for="kontak">Kontak/No. telepon</label>
                    <input type="text" id="kontak" name="kontak" value="{{ old('kontak') }}" required>
                    @error('kontak')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                  <!-- Alamat -->
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" required>
                    @error('alamat')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required>
                    @error('username')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="cta-btn-diteks">Daftar</button>
                </div>
            </form>

            <div class="form-footer">
                <p>Sudah memiliki akun? <a href="{{ route('login.form') }}">Masuk</a></p>
            </div>
        </div>
    </section>
@endsection
