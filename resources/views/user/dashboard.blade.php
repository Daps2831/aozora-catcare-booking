
@extends('layouts.app')
@section('title', 'User Dashboard')
@section('content')

<div class="dashboard-wrapper">
    {{-- Header Dashboard --}}
    <div class="dashboard-header">
        <h1><i class="fas fa-home"></i> Dashboard</h1>
        <p class="welcome-text">Selamat Datang Kembali, <strong>{{ $user->name }}</strong>!</p>
    </div>

    {{-- Dashboard Content --}}
    <div class="dashboard-content">
        
        {{-- Stats Cards --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-cat"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $kucingPengguna->count() }}</h3>
                    <p>Kucing Terdaftar</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $jadwalPengguna->filter(function($booking) {
                        return \Carbon\Carbon::parse($booking->tanggalBooking)->isFuture();
                    })->count() }}</h3>
                    <p>Booking Mendatang</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-scissors"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $jadwalPengguna->count() }}</h3>
                    <p>Total Grooming</p>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="main-content-grid">
            
            {{-- Bagian Kucing Saya --}}
            <div class="content-section">
                <div class="section-header">
                    <h2><i class="fas fa-paw"></i> Kucing Saya</h2>
                    <a href="{{ route('kucing.register') }}" class="btn-primary">
                        <i class="fas fa-plus"></i> Daftarkan Kucing
                    </a>
                </div>
                
                <div class="content-body">
                    @forelse($kucingPengguna as $kucing)
                        <div class="kucing-card">
                            <a href="{{ route('kucing.edit', $kucing->id) }}" class="kucing-link">
                                <div class="kucing-image">
                                    <img src="{{ $kucing->gambar ? asset('storage/' . $kucing->gambar) : asset('images/no-image-available.png') }}" 
                                         alt="Foto {{ $kucing->nama_kucing }}">
                                </div>
                                <div class="kucing-info">
                                    <h4>{{ $kucing->nama_kucing }}</h4>
                                    <div class="kucing-details">
                                        <span class="detail-item">
                                            <i class="fas fa-tag"></i>
                                            {{ $kucing->jenis }}
                                        </span>
                                        <span class="detail-item">
                                            <i class="fas fa-birthday-cake"></i>
                                            {{ $kucing->umur }} tahun
                                        </span>
                                    </div>
                                    <div class="kucing-actions">
                                        <span class="action-hint">
                                            <i class="fas fa-edit"></i> Klik untuk edit
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-cat"></i>
                            </div>
                            <h3>Belum Ada Kucing Terdaftar</h3>
                            <p>Daftarkan kucing Anda untuk mulai booking layanan grooming</p>
                            <a href="{{ route('kucing.register') }}" class="btn-primary">
                                <i class="fas fa-plus"></i> Daftarkan Kucing Pertama
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Bagian Jadwal Mendatang --}}
            <div class="content-section">
                <div class="section-header">
                    <h2><i class="fas fa-calendar-alt"></i> Jadwal Mendatang</h2>
                    <a href="{{ route('booking.index') }}" class="btn-secondary">
                        <i class="fas fa-plus"></i> Booking Baru
                    </a>
                </div>
                
                <div class="content-body">
                    @forelse($jadwalPengguna->filter(function($booking) {
                        return \Carbon\Carbon::parse($booking->tanggalBooking)->isFuture();
                    }) as $booking)
                        <div class="booking-card">
                            <div class="booking-header">
                                <div class="booking-date">
                                    <div class="date-day">{{ \Carbon\Carbon::parse($booking->tanggalBooking)->format('d') }}</div>
                                    <div class="date-info">
                                        <div class="date-month">{{ \Carbon\Carbon::parse($booking->tanggalBooking)->format('M Y') }}</div>
                                        <div class="date-time">{{ \Carbon\Carbon::parse($booking->jamBooking)->format('H:i') }}</div>
                                    </div>
                                </div>
                                <div class="booking-status status-{{ strtolower($booking->statusBooking) }}">
                                    {{ $booking->statusBooking }}
                                </div>
                            </div>
                            
                            <div class="booking-content">
                                <div class="booking-pets">
                                    @foreach($booking->kucings as $kucing)
                                        <div class="pet-item">
                                            <div class="pet-avatar">
                                                <img src="{{ $kucing->gambar ? asset('storage/' . $kucing->gambar) : asset('images/no-image-available.png') }}" 
                                                     alt="{{ $kucing->nama_kucing }}">
                                            </div>
                                            <div class="pet-info">
                                                <h5>{{ $kucing->nama_kucing }}</h5>
                                                <p>{{ $kucing->jenis }}</p>
                                                <div class="service-tag">
                                                    {{ $kucing->pivot->layanan_id 
                                                        ? optional(\App\Models\Layanan::find($kucing->pivot->layanan_id))->nama_layanan 
                                                        : 'Layanan belum dipilih' 
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($booking->alamatBooking)
                                    <div class="booking-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ Str::limit($booking->alamatBooking, 50) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h3>Tidak Ada Jadwal Mendatang</h3>
                            <p>Belum ada booking grooming yang dijadwalkan</p>
                            <a href="{{ route('booking.index') }}" class="btn-primary">
                                <i class="fas fa-plus"></i> Booking Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Quick Actions --}}
        <div class="quick-actions">
            <h3><i class="fas fa-bolt"></i> Aksi Cepat</h3>
            <div class="actions-grid">
                <a href="{{ route('booking.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="action-text">
                        <h4>Booking Grooming</h4>
                        <p>Jadwalkan layanan grooming untuk kucing Anda</p>
                    </div>
                </a>
                
                <a href="{{ route('kucing.register') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-text">
                        <h4>Daftar Kucing Baru</h4>
                        <p>Tambahkan kucing baru ke dalam sistem</p>
                    </div>
                </a>
                
                <a href="{{ route('customer.riwayat') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="action-text">
                        <h4>Lihat Riwayat</h4>
                        <p>Cek riwayat booking dan layanan sebelumnya</p>
                    </div>
                </a>
                
                <a href="{{ route('profile.show') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div class="action-text">
                        <h4>Kelola Profil</h4>
                        <p>Update informasi dan pengaturan akun</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection