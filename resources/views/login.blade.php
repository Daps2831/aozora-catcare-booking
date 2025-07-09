@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <section class="login-section">
        <div class="login-container">
            <h1>Masuk ke Akun Anda</h1>

            <!-- Menampilkan pesan sukses atau error jika ada -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <!-- Input Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol Masuk -->
                <div class="form-group">
                    <button type="submit" class="cta-btn-diteks">Masuk</button>
                </div>
            </form>

            <div class="form-footer">
                <p>Belum punya akun? <a href="{{ route('register.form') }}">Daftar</a></p>
            </div>
        </div>
    </section>
@endsection
