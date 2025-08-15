
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
                        <div class="booking-card {{ $booking->statusBooking == 'Batal' ? 'cancelled' : '' }}">
                            <div class="booking-header">
                                <div class="booking-date">
                                    <div class="date-day">{{ \Carbon\Carbon::parse($booking->tanggalBooking)->format('d') }}</div>
                                    <div class="date-info">
                                        <div class="date-month">{{ \Carbon\Carbon::parse($booking->tanggalBooking)->format('M Y') }}</div>
                                        <div class="date-time">{{ \Carbon\Carbon::parse($booking->jamBooking)->format('H:i') }}</div>
                                    </div>
                                </div>
                                <div class="booking-status status-{{ strtolower($booking->statusBooking) }}">
                                    @if($booking->statusBooking == 'Batal')
                                        <i class="fas fa-times-circle"></i>
                                    @endif
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

                                {{-- Tombol Batal Booking --}}
                                @if($booking->statusBooking != 'Batal' && $booking->statusBooking != 'Selesai')
                                    @php
                                        $bookingDateTime = \Carbon\Carbon::parse($booking->tanggalBooking . ' ' . $booking->jamBooking);
                                        $now = \Carbon\Carbon::now();
                                        $diffInHours = $now->diffInHours($bookingDateTime, false); // false = signed difference
                                        $canCancel = $diffInHours >= 2; // Minimal 2 jam ke depan
                                        
                                        // Debug info (hapus setelah testing)
                                        $debugInfo = [
                                            'booking_time' => $bookingDateTime->format('Y-m-d H:i:s'),
                                            'current_time' => $now->format('Y-m-d H:i:s'),
                                            'diff_hours' => $diffInHours,
                                            'can_cancel' => $canCancel
                                        ];
                                    @endphp
                                    
                                    {{-- Debug info (hapus setelah testing) --}}
                                    <div style="font-size: 10px; color: #666; margin: 5px 0;">
                                        <!-- Debug: Booking: {{ $debugInfo['booking_time'] }} | 
                                        Now: {{ $debugInfo['current_time'] }} | 
                                        Diff: {{ $debugInfo['diff_hours'] }}h | 
                                        Can Cancel: {{ $debugInfo['can_cancel'] ? 'Yes' : 'No' }} -->
                                    </div>
                                    
                                    @if($canCancel)
                                        <div class="booking-actions">
                                            <button class="btn-cancel" onclick="cancelBooking({{ $booking->id }})">
                                                <i class="fas fa-times"></i> Batal Booking
                                            </button>
                                        </div>
                                    @else
                                        <div class="booking-actions">
                                            <div class="cancel-disabled">
                                                <i class="fas fa-info-circle"></i>
                                                Pembatalan hanya bisa dilakukan minimal 2 jam sebelum jadwal
                                                <br><small>Sisa waktu: {{ $diffInHours > 0 ? number_format($diffInHours, 1) . ' jam' : 'Waktu sudah lewat' }}</small>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($booking->statusBooking == 'Batal')
                                    <div class="booking-actions">
                                        <div class="cancelled-notice">
                                            <i class="fas fa-ban"></i>
                                            Booking ini telah dibatalkan
                                        </div>
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

{{-- Modal Konfirmasi Pembatalan --}}
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true" style="z-index: 99999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Konfirmasi Pembatalan
                </h5>
                <button type="button" class="btn-close" onclick="hideModal()">×</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin membatalkan booking ini?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i>
                    Booking yang dibatalkan tidak dapat dikembalikan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="hideModal()">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmCancellation()">
                    <i class="fas fa-times"></i> Ya, Batalkan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* OVERRIDE APP.CSS CONFLICTS */
main {
    max-width: none !important;
    position: relative !important;
    z-index: 1 !important;
}

/* MODAL STYLES - SIMPLIFIED AND WORKING */
.modal {
    display: none;
    position: fixed;
    z-index: 99999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    align-items: center;
    justify-content: center;
}

.modal.show {
    display: flex !important;
}

.modal-dialog {
    position: relative;
    width: auto;
    max-width: 500px;
    margin: 20px;
    z-index: 100000;
}

.modal-content {
    position: relative;
    background-color: #fff;
    border: none;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    outline: 0;
    z-index: 100001;
    pointer-events: auto;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.modal-title {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-body {
    padding: 30px 20px;
    background: white;
}

.modal-footer {
    display: flex;
    justify-content: space-between;
    padding: 20px;
    border-top: 1px solid #e9ecef;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    background: #f8f9fa;
    gap: 15px;
}

.btn-close {
    background: none;
    border: none;
    font-size: 24px;
    font-weight: bold;
    color: #aaa;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.btn-close:hover {
    color: #000;
    background: rgba(0, 0, 0, 0.1);
}

/* BUTTON STYLING */
.btn {
    padding: 12px 24px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    font-size: 14px;
}

.btn:hover {
    transform: translateY(-2px);
    text-decoration: none;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* ALERT STYLING */
.alert {
    padding: 15px;
    margin: 15px 0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
    border-left: 4px solid #ffc107;
}

/* OVERRIDE Z-INDEX */
.navbar-wrapper, nav {
    z-index: 1000 !important;
}

.side-menu {
    z-index: 2001 !important;
}

.side-menu-overlay {
    z-index: 2000 !important;
}

.dashboard-wrapper,
.content-section,
.booking-card,
.kucing-card {
    z-index: 1 !important;
    position: relative !important;
}

/* BOOKING ACTIONS STYLING */
.booking-actions {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}

.btn-cancel {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9em;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-cancel:hover {
    background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.cancel-disabled {
    color: #6c757d;
    font-size: 0.85em;
    font-style: italic;
    display: flex;
    align-items: center;
    gap: 6px;
}

.cancelled-notice {
    color: #dc3545;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Status Batal */
.status-batal {
    background: rgba(220, 53, 69, 0.2);
    color: #721c24;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

/* Booking Card Cancelled */
.booking-card.cancelled {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-left: 5px solid #dc3545;
    opacity: 0.8;
}

.booking-card.cancelled .booking-header {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

/* BODY MODAL OPEN */
body.modal-open {
    overflow: hidden;
}

/* RESPONSIVE */
@media (max-width: 576px) {
    .modal-dialog {
        max-width: calc(100% - 20px);
        margin: 10px;
    }
    
    .modal-content {
        border-radius: 12px;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 15px;
    }
    
    .modal-footer {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
let bookingToCancel = null;

// Function to show modal
function cancelBooking(bookingId) {
    console.log('cancelBooking called with ID:', bookingId);
    bookingToCancel = bookingId;
    showModal();
}

// Function to show modal
function showModal() {
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
        console.log('Modal shown');
    }
}

// Function to hide modal
function hideModal() {
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        console.log('Modal hidden');
    }
}

// Function to confirm cancellation
function confirmCancellation() {
    if (bookingToCancel) {
        processBookingCancellation(bookingToCancel);
    } else {
        alert('Error: Booking ID tidak ditemukan');
    }
}

// Function to process booking cancellation
function processBookingCancellation(bookingId) {
    console.log('Processing cancellation for booking ID:', bookingId);
    
    // Update button state
    const confirmBtn = document.querySelector('.btn-danger');
    if (confirmBtn) {
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membatalkan...';
    }
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-times"></i> Ya, Batalkan';
        }
        return;
    }
    
    // Make request
    const url = `{{ url('/booking') }}/${bookingId}/cancel`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`Server error: ${response.status} - ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            hideModal();
            showSuccessNotification('Booking berhasil dibatalkan!');
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            throw new Error(data.message || 'Gagal membatalkan booking');
        }
    })
    .catch(error => {
        console.error('Cancel booking error:', error);
        alert('Gagal membatalkan booking: ' + error.message);
        
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-times"></i> Ya, Batalkan';
        }
    });
}

// Function to show success notification
function showSuccessNotification(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 100000;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        max-width: 300px;
        word-wrap: break-word;
    `;
    notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 4000);
}

// Event listeners setup
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, modal setup complete');
    
    // Click outside modal to close
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('cancelModal');
        if (e.target === modal) {
            hideModal();
        }
    });
    
    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('cancelModal');
            if (modal && modal.classList.contains('show')) {
                hideModal();
            }
        }
    });
});
</script>

@endsection