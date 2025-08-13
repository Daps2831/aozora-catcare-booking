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
            
            {{-- Informasi jam operasional dan batasan --}}
            <div style="margin-top: 0.5rem; padding: 8px; background: #e3f2fd; border-radius: 4px; font-size: 0.9em;">
                <strong>Jam Operasional:</strong> 08:00 - 18:30<br>
                @if(\Carbon\Carbon::parse($selectedDate)->isToday())
                    <span style="color: #d32f2f;">
                        <i class="fas fa-info-circle"></i>
                        Booking minimal 2 jam dari sekarang ({{ \Carbon\Carbon::now()->addHours(2)->format('H:i') }})
                    </span>
                @endif
            </div>
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
            
            <div class="checkbox-group" style="border: 1px solid #ccc; padding: 10px; border-radius: 5px; max-height: 300px; overflow-y: auto;">
                
                {{-- ======================================================= --}}
                {{-- ============ BAGIAN BARU: HEADER KOLOM ================ --}}
                {{-- ======================================================= --}}
                <div class="kucing-item header-row" style="padding-bottom: 0.5rem; margin-bottom: 0.5rem; font-weight: bold; color: #333;">
                    <div class="kucing-info">
                        <label for="select-all-kucing" style="cursor: pointer; display: flex; align-items: center; gap: 0.75rem;">
                            <input type="checkbox" id="select-all-kucing" style="vertical-align: middle;">
                            <span>Gambar</span>
                        </label>
                    </div>
                    <div class="kucing-jenis">Jenis Kucing</div>
                    <div class="layanan-container">Layanan</div>
                </div>
                {{-- ======================================================= --}}
                {{-- ================== AKHIR BAGIAN BARU ================== --}}
                {{-- ======================================================= --}}

                @forelse($kucings as $kucing)
                    <div class="kucing-item">
                        
                        {{-- KOLOM 1: Checkbox, Gambar, dan Nama Kucing --}}
                        <label class="kucing-info" for="kucing_{{ $kucing->id }}">
                            <input type="checkbox" name="kucing_ids[]" value="{{ $kucing->id }}"
                                id="kucing_{{ $kucing->id }}"
                                class="kucing-checkbox"
                                {{ in_array($kucing->id, old('kucing_ids', [])) ? 'checked' : '' }}>
                            <img src="{{ $kucing->gambar ? asset('storage/' . $kucing->gambar) : asset('images/no-image-available.png') }}"
                                alt="Foto {{ $kucing->nama_kucing }}">
                            <span>{{ $kucing->nama_kucing }}</span>
                        </label>

                        {{-- KOLOM 2: Jenis Kucing --}}
                        <div class="kucing-jenis">
                            {{ $kucing->jenis }}
                        </div>

                        {{-- KOLOM 3: Dropdown Layanan --}}
                        <div class="layanan-container" id="layanan_container_{{ $kucing->id }}" style="{{ in_array($kucing->id, old('kucing_ids', [])) ? '' : 'display:none;' }}">
                            <select name="layanan_per_kucing[{{ $kucing->id }}]" class="form-control" style="min-width:160px;">
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($layanans as $layanan)
                                    <option value="{{ $layanan->id }}"
                                        data-harga="{{ $layanan->harga }}"
                                        {{ old('layanan_per_kucing.' . $kucing->id) == $layanan->id ? 'selected' : '' }}>
                                        {{ $layanan->nama_layanan }} - Rp {{ number_format($layanan->harga, 0, ',', '.') }}
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

    {{-- Tambahkan di bawah form --}}
    <div id="hargaTotalContainer" style="margin-top: 20px; font-weight: bold;">
        Total Harga: <span id="hargaTotal">Rp 0</span>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route('booking.index') }}" class="btn-back" style="width: 100%; max-width: none;">Kembali ke Pilih Tanggal</a>
</div>
@endsection

<style>
/* =============================================== */
/* BOOKING TIME VALIDATION STYLES */
/* =============================================== */

.alert.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    padding: 10px;
    margin-top: 10px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.alert.alert-danger::before {
    content: '⚠';
    font-size: 16px;
    color: #721c24;
}

/* Disabled submit button style */
button[type="submit"]:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    background-color: #6c757d !important;
}

/* Time input validation styles */
input[type="time"]:invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Info box untuk jam operasional */
.time-info-box {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    padding: 12px;
    margin-top: 8px;
    border-radius: 4px;
    font-size: 14px;
    line-height: 1.4;
}

.time-info-box i {
    color: #2196f3;
    margin-right: 5px;
}

.time-warning {
    color: #d32f2f;
    font-weight: 500;
}


@media (max-width: 768px) {
    .container {
        width: 100%;
        padding: 10px;
    }

    .form-group {
        flex-direction: column;
        align-items: flex-start;
    }

    .checkbox-group {
        max-height: 200px; /* Kurangi tinggi untuk layar kecil */
        overflow-y: auto;
    }

    .kucing-item {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .kucing-info img {
        max-width: 100px; /* Ukuran gambar lebih kecil */
        height: auto;
    }

    .layanan-container select {
        width: 100%; /* Dropdown memenuhi lebar layar */
    }

    .alert.alert-danger {
        font-size: 13px;
        padding: 8px;
    }
    
    .time-info-box {
        font-size: 13px;
        padding: 10px;
    }
}
</style>