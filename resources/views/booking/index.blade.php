
@extends('layouts.app')

@section('title', 'Jadwal Booking')

@section('content')
<div class="booking-calendar-container">
    <!-- Header Section -->
    <div class="calendar-header">
        <div class="header-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="4"/>
                <path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
        </div>
        <h2>Pilih Tanggal Booking</h2>
        <p class="header-description">
            Pilih tanggal yang tersedia di kalender.<br>
            Tanggal sebelum hari ini dinonaktifkan dan tanggal yang sudah penuh (10 kucing) akan dinonaktifkan.
        </p>
    </div>

    <!-- Calendar Legend -->
    <div class="calendar-legend">
        <h3>Keterangan Kalender</h3>
        
        <!-- Example Container -->
        <div class="legend-example">
            <div class="legend-item example-container">
                <div class="booking-example">
                    <div class="booking-time">08:00 - 11:15</div>
                    <div class="booking-details">
                        <span class="cat-count">3 kucing</span>
                        <span class="team-badge">Tim A</span>
                    </div>
                </div>
                <span class="legend-label">Contoh Container Booking</span>
            </div>
        </div>
        
        <!-- Legend Items -->
        <div class="legend-items">
            <div class="legend-item">
                <div class="legend-demo time-demo">08:00 - 11:15</div>
                <span class="legend-label">Jam Booking</span>
            </div>
            
            <div class="legend-item">
                <div class="legend-demo cat-demo">3 kucing</div>
                <span class="legend-label">Jumlah Kucing</span>
            </div>
            
            <div class="legend-item">
                <div class="legend-demo team-demo">Tim A</div>
                <span class="legend-label">Tim Groomer</span>
            </div>
        </div>
        
        <!-- Notes -->
        <div class="calendar-notes">
            <div class="note">
                <strong>Catatan:</strong> Setiap <strong>container</strong> (seperti contoh di atas) di dalam tanggal pada kalender menandakan sudah ada yang booking di tanggal <strong>dan</strong> jam tersebut.
            </div>
            <div class="note">
                Tim Groomer akan dipilih oleh admin, jadi anda tidak perlu memilih tim groomer.
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="calendar-wrapper">
        <div id="calendar" class="calendar-customer"
            data-full-dates='@json($fullDates ?? [])'
            data-disabled-dates-data="{{ json_encode($disabledDatesData) }}"
            data-events='@json($events ?? [])'>
        </div>
    </div>

    <!-- Calendar Info & Actions -->
    <div class="calendar-actions">
        <div id="calendar-time-info" class="time-info"></div>
        <div class="action-buttons">
            <button id="btn-konfirmasi-booking" class="btn-confirm" disabled>
                <i class="fas fa-check"></i>
                Konfirmasi
            </button>
            <a href="{{ route('user.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.css" rel="stylesheet" />
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js"></script>
@endsection