@extends('layouts.app')

@section('title', 'Pendaftaran Data Kucing')

@section('content')
    <section class="register-kucing-section">
        <div class="register-container">
            <h1>Daftar Data Kucing Anda</h1>

            <!-- Menampilkan pesan sukses atau error -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Form Pendaftaran Kucing -->
            <form action="{{ route('kucing.store') }}" method="POST">
                @csrf

                <!-- Nama Kucing -->
                <div class="form-group">
                    <label for="namaKucing">Nama Kucing</label>
                    <input type="text" id="namaKucing" name="namaKucing" value="{{ old('namaKucing') }}" required>
                    @error('namaKucing')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jenis Kucing -->
                <div class="form-group">
                    <label for="jenis">Jenis Kucing</label>
                    <input type="text" id="jenis" name="jenis" value="{{ old('jenis') }}" required>
                    @error('jenis')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Umur Kucing -->
                <div class="form-group">
                    <label for="umur">Umur Kucing (tahun)</label>
                    <input type="number" id="umur" name="umur" value="{{ old('umur') }}" required>
                    @error('umur')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Riwayat Kesehatan -->
                <div class="form-group">
                    <label for="riwayatKesehatan">Riwayat Kesehatan</label>
                    <textarea id="riwayatKesehatan" name="riwayatKesehatan">{{ old('riwayatKesehatan') }}</textarea>
                    @error('riwayatKesehatan')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <div class="form-group">
                    <button type="submit" class="cta-btn-diteks">Daftar</button>
                </div>
            </form>
        </div>
    </section>
@endsection
