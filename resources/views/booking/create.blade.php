@extends('layouts.app')

@section('title', 'Buat Booking')

@section('content')
<div class="container" style="max-width: 600px; margin: 40px auto;">
    <h1>Formulir Booking</h1>
    <p>Anda akan membuat booking untuk tanggal: <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</strong></p>

    @if(session('error'))
        <div style="color: red; margin-bottom: 15px;">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        {{-- Loop untuk setiap pesan error yang unik --}}
        @foreach (array_unique($errors->all()) as $error)
            {{-- Buat satu kontainer div untuk setiap pesan --}}
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endforeach
    @endif

 

    <form id="booking-form" action="{{ route('booking.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tanggalBooking" value="{{ $selectedDate }}">
        
        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="jamBookingManual">Pilih Jam Booking</label>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <input
                    type="range"
                    id="jamBookingSlider"
                    min="8"
                    max="18"
                    step="0.5"
                    value="8"
                    style="flex:2;"
                >
                <input
                    type="time"
                    id="jamBookingManual"
                    name="jamBooking"
                    min="08:00"
                    max="18:30"
                    step="600"
                    value="08:00"
                    required
                    style="flex:1;"
                >
            </div>
            <small id="jamBookingLabel" style="display:block;margin-top:0.5rem;">Jam dipilih: 08:00</small>
        </div>

        <div class="form-group alamat-container" style="margin-bottom: 1rem;">
            <label for="alamatBookingInput">Alamat Booking</label>
            <div class="alamat-options">
                <label class="alamat-radio">
                    <input type="radio" name="alamat_option" id="alamat_default" value="default" checked>
                    <span>Gunakan alamat sesuai profil (<span id="alamatDefault">{{ $user->customer->alamat ?? '-' }}</span>)</span>
                </label>
                <label class="alamat-radio">
                    <input type="radio" name="alamat_option" id="alamat_manual" value="manual">
                    <span>Masukkan alamat lain</span>
                </label>
            </div>
            <input type="text" name="alamatBooking" id="alamatBookingInput"
                class="form-control"
                placeholder="Masukkan alamat booking"
                value="{{ $user->customer->alamat ?? '' }}"
                required
                style="margin-top:0.5rem;"
                readonly
            >
        </div>

        {{-- Container Pilih Kucing --}}
        <div class="form-group" style="margin-top: 1.5rem;">
            <span style="font-weight:600; display: block; margin-bottom: 0.5rem;">Pilih Kucing (bisa lebih dari satu)</span>
            
            {{-- PASTIKAN DIV DI BAWAH INI MEMILIKI STYLE YANG LENGKAP --}}
            <div class="checkbox-group" style="border: 1px solid #ccc; padding: 10px; border-radius: 5px; max-height: 300px; overflow-y: auto;">
                
                @forelse($kucings as $kucing)
                    {{-- Ini adalah container untuk satu baris kucing --}}
                    <div class="kucing-item">
                        
                        {{-- KOLOM 1: Diberi lebar tetap 220px --}}
                        <label class="kucing-info" for="kucing_{{ $kucing->id }}">
                            <input type="checkbox" name="kucing_ids[]" value="{{ $kucing->id }}"
                                id="kucing_{{ $kucing->id }}"
                                class="kucing-checkbox"
                                {{ in_array($kucing->id, old('kucing_ids', [])) ? 'checked' : '' }}>
                            <img src="{{ $kucing->gambar ? asset('storage/' . $kucing->gambar) : asset('images/no-image-available.png') }}"
                                alt="Foto {{ $kucing->nama_kucing }}">
                            <span>{{ $kucing->nama_kucing }}</span>
                        </label>

                        {{-- KOLOM 2: Diberi lebar tetap 150px --}}
                        <div class="kucing-jenis" >
                            Jenis: {{ $kucing->jenis }}
                        </div>

                        {{-- KOLOM 3: Mengisi sisa ruang --}}
                        <div class="layanan-container" id="layanan_container_{{ $kucing->id }}">
                            <select name="layanan_per_kucing[{{ $kucing->id }}]" class="form-control">
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($layanans as $layanan)
                                    <option value="{{ $layanan->id }}"
                                        {{ old('layanan_per_kucing.' . $kucing->id) == $layanan->id ? 'selected' : '' }}>
                                        {{ $layanan->nama_layanan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                @empty
                    <p>Anda belum memiliki data kucing. <a href="{{ route('kucing.register') }}">Daftarkan kucing sekarang</a>.</p>
                @endforelse

            </div>
        </div>

        <button type="submit" class="cta-btn-diteks" style="width: 100%; max-width: none;">Kirim Booking</button>
    </form>

    <div style="margin-top: 20px;">
        <a href="{{ route('booking.index') }}" class="btn-back" style="width: 100%; max-width: none;">Kembali ke Pilih Tanggal</a>
</div>
@endsection