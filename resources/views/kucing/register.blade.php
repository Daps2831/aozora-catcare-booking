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
            <form action="{{ route('kucing.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- TAMBAHKAN FORM GROUP INI UNTUK GAMBAR --}}
                <div class="form-group">
                    <label for="gambar">Foto Kucing (Opsional)</label>
                    {{-- Tambahkan id="gambar-input" --}}
                    <input type="file" id="gambar-input" name="gambar" accept="image/*">
                    @error('gambar')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    {{-- Tambahkan elemen ini untuk menampung preview --}}
                    <img id="image-preview" src="#" alt="Preview Gambar" style="display:none; max-width: 200px; margin-top: 15px; border-radius: 8px;" />
                </div>

                <!-- Nama Kucing -->
                <div class="form-group">
                    <label for="nama_kucing">Nama Kucing</label>
                    <input type="text" id="nama_kucing" name="nama_kucing" value="{{ old('nama_kucing') }}" required>
                    @error('nama_kucing')
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

{{-- TAMBAHKAN BLOK KODE INI DI BAGIAN PALING BAWAH --}}
@section('scripts')  
    <script src="{{ asset('js/script.js') }}"></script>
@endsection