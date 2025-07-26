{{-- file: resources/views/user/dashboard.blade.php --}}

@extends('layouts.app')
@section('title', 'User Dashboard')
@section('content')

  
    {{-- =============================================== --}}
    {{-- konten dashboard  --}}
    {{-- =============================================== --}}

    {{-- Wadah untuk Salam Pembuka --}}
    <div class="welcome-container">
        <h2>Selamat Datang Kembali, {{ $user->name }}!</h2>
    </div>

    {{-- Wadah untuk Seluruh Konten Dashboard --}}
    <div class="dashboard-content">
        {{-- Bagian Kucing Saya --}}
        <div class="cat-section-container">
            <div class="cat-section-header">
                <h3>Kucing Saya</h3>
                <a href="{{ route('kucing.register') }}" class="btn-tambah-kucing">+ Daftarkan Kucing</a>
            </div>
            <div class="cat-grid">
                @forelse($kucingPengguna as $kucing)
                    <a href="{{ route('kucing.edit', $kucing->id) }}" class="cat-card-link">
                        <div class="cat-card">
                            <img src="{{ $kucing->gambar ? asset('storage/' . $kucing->gambar) : asset('images/no-image-available.png') }}" alt="Foto {{ $kucing->nama_kucing }}">
                            <div class="cat-info">
                                <h4>{{ $kucing->nama_kucing }}</h4>
                                <p>Jenis: {{ $kucing->jenis }}</p>
                                <p>Umur: {{ $kucing->umur }} tahun</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p style="text-align: left; width: 100%;">Anda belum memiliki data kucing.</p>
                @endforelse
            </div>
        </div>

        {{-- TAMBAHKAN BLOK BARU INI --}}
        <div class="booking-section-container">
            <div class="booking-section-header">
                <h3>Jadwal Mendatang</h3>
                <a href="{{ route('booking.index') }}" class="btn-booking-sekarang">Booking Sekarang</a>
            </div>
            <div class="booking-list">
               @forelse($jadwalPengguna->filter(function($booking) {
                    return \Carbon\Carbon::parse($booking->tanggalBooking)->isFuture();
                }) as $booking)
                    <div class="booking-item">
                        <p>
                            @foreach($booking->kucings as $kucing)
                                <p>
                                    <strong>{{ $kucing->nama_kucing }}</strong>
                                    (Jenis: {{ $kucing->jenis }})<br>
                                    Layanan: 
                                    {{ $kucing->pivot->layanan_id 
                                        ? optional(\App\Models\Layanan::find($kucing->pivot->layanan_id))->nama_layanan 
                                        : '-' 
                                    }}
                                </p>
                            @endforeach
                        </p>
                        <p class="booking-details">
                            Tanggal: {{ \Carbon\Carbon::parse($booking->tanggalBooking)->format('d F Y') }}
                            <br>
                            Alamat: {{ $booking->alamatBooking ?? '-' }}
                        </p>
                    </div>
                @empty
                    <p>Tidak ada jadwal grooming yang akan datang.</p>
                @endforelse
            </div>
        </div>
        {{-- AKHIR BLOK BARU --}}
    </div>
@endsection

