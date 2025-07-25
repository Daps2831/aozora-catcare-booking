@extends('layouts.app')

@section('title', 'Edit Data Kucing')

@section('content')
    <div class="form-container">
        <h1>Edit Data: {{ $kucing->nama_kucing }}</h1>

        <form action="{{ route('kucing.update', $kucing->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Elemen untuk menampilkan gambar saat ini ATAU preview gambar baru --}}
            <div class="image-preview-container">
                <p>Foto Kucing:</p>
                <img id="image-preview" 
                    src="{{ $kucing->gambar ? asset('storage/' . $kucing->gambar) : asset('images/no-image-available.png') }}" 
                    alt="Preview Gambar" 
                    style="max-width: 200px; margin-bottom: 15px; border-radius: 8px;">
            </div>

            {{-- Input untuk mengganti gambar --}}
            <div class="form-group">
                <label for="gambar-input">Ganti Foto Kucing</label>
                <input type="file" id="gambar-input" name="gambar" accept="image/*">
                <span id="file-name" style="display:none; margin-top:8px; font-weight:500;"></span>
                <button type="button" id="btn-ganti-foto" style="display:none; margin-left:10px;">Ganti Foto</button>
                @error('gambar')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Form Group lainnya (Nama, Jenis, Umur, dll.) --}}
            <div class="form-group">
                <label for="nama_kucing">Nama Kucing</label>
                <input type="text" id="nama_kucing" name="nama_kucing" value="{{ old('nama_kucing', $kucing->nama_kucing) }}" required>
            </div>

            <div class="form-group">
                <label for="jenis">Jenis Kucing</label>
                <input type="text" id="jenis" name="jenis" value="{{ old('jenis', $kucing->jenis) }}" required>
            </div>
            
            <div class="form-group">
                <label for="umur">Umur Kucing (tahun)</label>
                <input type="number" id="umur" name="umur" value="{{ old('umur', $kucing->umur) }}" required>
            </div>

            <div class="form-group">
                <label for="riwayat_kesehatan">Riwayat Kesehatan</label>
                <textarea id="riwayat_kesehatan" name="riwayat_kesehatan">{{ old('riwayat_kesehatan', $kucing->riwayat_kesehatan) }}</textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="cta-btn-diteks">Simpan Perubahan</button>
            </div>

            <div class="form-group">
                <a href="{{ route('user.dashboard') }}" class="btn-cancel">Batal</a>
            </div>

        </form>
    </div>
@endsection

