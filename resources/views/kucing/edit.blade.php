
@extends('layouts.app')

@section('title', 'Edit Data Kucing')

@section('content')
    <section class="register-kucing-section">
        <div class="register-container">
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

                <div class="form-group" style="display:flex; gap:16px;">
                    <button type="submit" class="cta-btn-diteks btn-form-kucing">Simpan Perubahan</button>
                    <a href="{{ route('user.dashboard') }}" class="btn-back btn-form-kucing">Batal</a>
                </div>
            </form>

            {{-- Tombol Hapus --}}
            <form action="{{ route('kucing.destroy', $kucing->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data kucing ini?')" style="margin-top:10px;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-hapus" style="background:#e11d48;color:#fff;border:none;border-radius:6px;padding:0.6rem 1.5rem;font-size:1rem;font-weight:600;cursor:pointer;">
                    Hapus
                </button>
            </form>
        </div>
    </section>
@endsection