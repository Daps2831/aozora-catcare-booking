
@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
<div class="riwayat-container">
    <div class="riwayat-header">
        <h2><i class="fas fa-history"></i> Riwayat Booking Anda</h2>
        <p class="subtitle">Lihat semua booking grooming yang pernah Anda lakukan</p>
    </div>

    @if($riwayatBookings->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3>Belum Ada Riwayat Booking</h3>
            <p>Anda belum pernah melakukan booking grooming.</p>
            <a href="{{ route('booking.index') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Booking Sekarang
            </a>
        </div>
    @else
        <div class="booking-timeline">
            @foreach($riwayatBookings as $booking)
                <div class="booking-card {{ strtolower($booking->statusBooking) }}">
                    {{-- Header Card --}}
                    <div class="booking-card-header">
                        <div class="booking-id">
                            <span class="id-label">Booking #{{ $booking->id }}</span>
                            <span class="booking-date">
                                <i class="fas fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($booking->tanggalBooking)->format('d F Y') }}
                            </span>
                        </div>
                        <div class="booking-status">
                            <span class="status-badge status-{{ strtolower($booking->statusBooking) }}">
                                @if($booking->statusBooking == 'Pending')
                                    <i class="fas fa-clock"></i>
                                @elseif($booking->statusBooking == 'Proses')
                                    <i class="fas fa-spinner"></i>
                                @else
                                    <i class="fas fa-check-circle"></i>
                                @endif
                                {{ $booking->statusBooking }}
                            </span>
                        </div>
                    </div>

                    {{-- Booking Details --}}
                    <div class="booking-details">
                        <div class="detail-row">
                            <div class="detail-item">
                                <i class="fas fa-clock text-primary"></i>
                                <div class="detail-content">
                                    <span class="detail-label">Waktu</span>
                                    <span class="detail-value">{{ $booking->jamBooking ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-users text-info"></i>
                                <div class="detail-content">
                                    <span class="detail-label">Tim Groomer</span>
                                    <span class="detail-value">{{ $booking->tim->nama_tim ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-item full-width">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <div class="detail-content">
                                    <span class="detail-label">Alamat</span>
                                    <span class="detail-value">{{ $booking->alamatBooking ?? 'Tidak ada alamat' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kucing & Layanan --}}
                    <div class="kucing-section">
                        <h4><i class="fas fa-cat"></i> Kucing & Layanan</h4>
                        <div class="kucing-grid">
                            @foreach($booking->kucings as $kucing)
                                <div class="kucing-item">
                                    {{-- Gambar Kucing --}}
                                    <div class="kucing-image">
                                        @if($kucing->gambar && file_exists(storage_path('app/public/' . $kucing->gambar)))
                                            <img src="{{ asset('storage/' . $kucing->gambar) }}" 
                                                 alt="{{ $kucing->nama_kucing }}" 
                                                 class="kucing-photo">
                                        @else
                                            <div class="kucing-placeholder">
                                                <i class="fas fa-cat"></i>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info Kucing --}}
                                    <div class="kucing-info">
                                        <h5>{{ $kucing->nama_kucing }}</h5>
                                        <p class="kucing-breed">{{ $kucing->jenis }} • {{ $kucing->umur }} tahun</p>
                                        
                                        {{-- Layanan --}}
                                        @php
                                            $layanan = $kucing->pivot->layanan_id ? \App\Models\Layanan::find($kucing->pivot->layanan_id) : null;
                                        @endphp
                                        <div class="layanan-info">
                                            <span class="layanan-badge">
                                                <i class="fas fa-cut"></i>
                                                {{ $layanan->nama_layanan ?? 'Tidak ada layanan' }}
                                            </span>
                                            @if($layanan && $layanan->harga)
                                                <span class="layanan-price">
                                                    Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>

                                      

                                        {{-- Catatan Grooming (Hanya untuk booking selesai) --}}
                                        @if($booking->statusBooking == 'Selesai')
                                            <div class="catatan-section">
                                                <h6><i class="fas fa-clipboard-list"></i> Catatan Grooming:</h6>
                                                
                                                @if(!empty($kucing->pivot->catatan) && $kucing->pivot->catatan !== '')
                                                    <div class="catatan-content">
                                                        <div class="catatan-box">
                                                            <i class="fas fa-quote-left"></i>
                                                            <p>{{ $kucing->pivot->catatan }}</p>
                                                            <i class="fas fa-quote-right"></i>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="no-catatan">
                                                        <i class="fas fa-info-circle"></i>
                                                        Tidak ada catatan khusus untuk {{ $kucing->nama_kucing }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Total Harga (untuk booking selesai) --}}
                    @if($booking->statusBooking == 'Selesai')
                        @php
                            $totalHarga = 0;
                            foreach($booking->kucings as $kucing) {
                                $layanan = $kucing->pivot->layanan_id ? \App\Models\Layanan::find($kucing->pivot->layanan_id) : null;
                                if($layanan) $totalHarga += $layanan->harga;
                            }
                        @endphp
                        @if($totalHarga > 0)
                            <div class="total-section">
                                <div class="total-price">
                                    <span class="total-label">Total Pembayaran:</span>
                                    <span class="total-amount">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pagination jika ada --}}
        @if(method_exists($riwayatBookings, 'links'))
            <div class="pagination-wrapper">
                {{ $riwayatBookings->links() }}
            </div>
        @endif
    @endif
</div>




<style>
/* =============================================== */
/* RIWAYAT PAGE - RESET LAYOUT CONFLICTS */
/* =============================================== */

/* PENTING: Reset main container untuk halaman ini */
body {
    position: static !important;
}

main {
    position: static !important;
    z-index: auto !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Container riwayat dengan margin yang aman */
.riwayat-container {
    max-width: 900px;
    margin: 80px auto 40px auto; /* Tambah top margin untuk navbar */
    padding: 0 20px;
    position: static;
    z-index: auto;
    clear: both;
}

/* Reset untuk semua elemen dalam container */
.riwayat-container * {
    box-sizing: border-box;
    position: static;
}

/* Header */
.riwayat-header {
    text-align: center;
    margin-bottom: 40px;
    padding-top: 20px;
}

.riwayat-header h2 {
    color: #2c3e50;
    font-size: 2.2em;
    margin-bottom: 10px;
    font-weight: 600;
}

.riwayat-header .subtitle {
    color: #7f8c8d;
    font-size: 1.1em;
    margin: 0;
}

/* Empty State */
.riwayat-container .empty-state {
    text-align: center;
    padding: 60px 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    border: 2px dashed #dee2e6;
}

.riwayat-container .empty-icon {
    font-size: 4em;
    color: #adb5bd;
    margin-bottom: 20px;
}

.riwayat-container .empty-state h3 {
    color: #495057;
    margin-bottom: 10px;
}

.riwayat-container .empty-state p {
    color: #6c757d;
    margin-bottom: 30px;
}

/* Booking Cards */
.riwayat-container .booking-timeline {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.riwayat-container .booking-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-left: 5px solid;
    position: static;
}

.riwayat-container .booking-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.riwayat-container .booking-card.selesai {
    border-left-color: #28a745;
}

.riwayat-container .booking-card.proses {
    border-left-color: #17a2b8;
}

.riwayat-container .booking-card.pending {
    border-left-color: #ffc107;
}

/* Card Header */
.riwayat-container .booking-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.riwayat-container .booking-id .id-label {
    font-size: 1.2em;
    font-weight: 600;
    display: block;
}

.riwayat-container .booking-date {
    font-size: 0.9em;
    opacity: 0.9;
    margin-top: 5px;
    display: block;
}

/* Status Badge */
.riwayat-container .status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.riwayat-container .status-selesai {
    background: rgba(40, 167, 69, 0.2);
    color: #155724;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.riwayat-container .status-proses {
    background: rgba(23, 162, 184, 0.2);
    color: #0c5460;
    border: 1px solid rgba(23, 162, 184, 0.3);
}

.riwayat-container .status-pending {
    background: rgba(255, 193, 7, 0.2);
    color: #856404;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

/* Booking Details */
.riwayat-container .booking-details {
    padding: 20px;
    background: #f8f9fa;
}

.riwayat-container .detail-row {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.riwayat-container .detail-row:last-child {
    margin-bottom: 0;
}

.riwayat-container .detail-item {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.riwayat-container .detail-item.full-width {
    flex: none;
    width: 100%;
}

.riwayat-container .detail-item i {
    font-size: 1.2em;
    width: 20px;
    text-align: center;
}

.riwayat-container .detail-content {
    display: flex;
    flex-direction: column;
}

.riwayat-container .detail-label {
    font-size: 0.8em;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.riwayat-container .detail-value {
    font-weight: 500;
    color: #2c3e50;
}

/* Kucing Section */
.riwayat-container .kucing-section {
    padding: 20px;
}

.riwayat-container .kucing-section h4 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 1.1em;
}

.riwayat-container .kucing-grid {
    display: grid;
    gap: 20px;
}

/* Kucing Item - Override global grid CSS */
.riwayat-container .kucing-item {
    display: flex !important;
    grid-template-columns: none !important;
    gap: 15px;
    align-items: flex-start;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    flex-direction: column;
    cursor: default;
    font-weight: normal;
    height: auto !important;
}

/* Desktop layout */
@media (min-width: 769px) {
    .riwayat-container .kucing-item {
        flex-direction: row !important;
    }
}

.riwayat-container .kucing-image {
    flex-shrink: 0;
}

.riwayat-container .kucing-photo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.riwayat-container .kucing-placeholder {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #e9ecef, #dee2e6);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 2em;
}

.riwayat-container .kucing-info {
    flex: 1;
    min-width: 0;
    display: block !important;
    grid-template-columns: none !important;
    align-items: unset !important;
    font-weight: normal !important;
    cursor: default !important;
    border-right: none !important;
    padding-right: 0 !important;
    height: auto !important;
}

.riwayat-container .kucing-info h5 {
    color: #2c3e50;
    margin-bottom: 5px;
    font-weight: 600;
}

.riwayat-container .kucing-breed {
    color: #6c757d;
    font-size: 0.9em;
    margin-bottom: 10px;
}

.riwayat-container .layanan-info {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.riwayat-container .layanan-badge {
    background: #007bff;
    color: white;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.8em;
    font-weight: 500;
}

.riwayat-container .layanan-price {
    color: #28a745;
    font-weight: 600;
    font-size: 0.9em;
}

/* CATATAN SECTION */
.riwayat-container .catatan-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 3px dashed #007bff;
    clear: both;
    width: 100%;
    position: static;
    display: block;
    overflow: visible;
}

.riwayat-container .catatan-section h6 {
    color: #495057;
    font-size: 1.1em;
    margin-bottom: 15px;
    font-weight: 600;
    display: block;
}

.riwayat-container .catatan-content {
    width: 100%;
    margin-top: 10px;
    display: block;
}

.riwayat-container .catatan-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
    border-radius: 12px;
    position: static;
    font-style: italic;
    min-height: 80px;
    display: block;
    width: 100%;
    box-sizing: border-box;
    margin: 10px 0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: visible;
    text-align: center;
}

.riwayat-container .catatan-box p {
    margin: 0;
    padding: 0 30px;
    line-height: 1.6;
    font-size: 1.1em;
    font-weight: 500;
    position: static;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.riwayat-container .catatan-box i.fa-quote-left {
    position: absolute;
    top: 15px;
    left: 15px;
    opacity: 0.5;
    font-size: 1.3em;
}

.riwayat-container .catatan-box i.fa-quote-right {
    position: absolute;
    bottom: 15px;
    right: 15px;
    opacity: 0.5;
    font-size: 1.3em;
}

.riwayat-container .no-catatan {
    color: #6c757d;
    font-style: italic;
    font-size: 1em;
    margin: 10px 0;
    padding: 15px;
    background: #f8f9fa;
    border: 1px dashed #dee2e6;
    border-radius: 8px;
    text-align: center;
    display: block;
}

.riwayat-container .no-catatan i {
    margin-right: 8px;
    color: #007bff;
}

/* Total Section */
.riwayat-container .total-section {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    padding: 20px;
    margin-top: 0;
}

.riwayat-container .total-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.riwayat-container .total-label {
    font-size: 1.1em;
    font-weight: 500;
}

.riwayat-container .total-amount {
    font-size: 1.4em;
    font-weight: 700;
}

/* Buttons */
.riwayat-container .btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: transform 0.3s ease;
}

.riwayat-container .btn-primary:hover {
    transform: translateY(-2px);
    text-decoration: none;
    color: white;
}

/* Pagination */
.riwayat-container .pagination-wrapper {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}

.riwayat-container .booking-card.batal {
    border-left-color: #dc3545;
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    opacity: 0.8;
}

.riwayat-container .status-batal {
    background: rgba(220, 53, 69, 0.2);
    color: #721c24;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

/* RESPONSIVE DESIGN */
@media (max-width: 768px) {
    .riwayat-container {
        padding: 0 15px;
        margin: 60px auto 20px auto; /* Kurangi top margin di mobile */
    }

    .riwayat-header h2 {
        font-size: 1.8em;
    }

    .riwayat-container .booking-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .riwayat-container .detail-row {
        flex-direction: column;
        gap: 15px;
    }

    .riwayat-container .kucing-item {
        flex-direction: column !important;
        text-align: left;
    }

    .riwayat-container .kucing-image {
        align-self: center;
        margin-bottom: 15px;
    }

    .riwayat-container .layanan-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .riwayat-container .total-price {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .riwayat-container .catatan-box {
        padding: 20px;
    }

    .riwayat-container .catatan-box p {
        padding: 0 25px;
        font-size: 1em;
    }
}

@media (max-width: 480px) {
    .riwayat-container {
        margin: 40px auto 20px auto; /* Lebih kecil lagi di mobile kecil */
    }

    .riwayat-container .kucing-photo,
    .riwayat-container .kucing-placeholder {
        width: 60px;
        height: 60px;
    }

    .riwayat-container .booking-card {
        margin: 0 -5px;
    }

    .riwayat-container .catatan-box {
        padding: 15px;
        min-height: 60px;
    }

    .riwayat-container .catatan-box p {
        padding: 0 20px;
        font-size: 0.95em;
    }
}
</style>