
@extends('adminlte::page')
@section('title', 'Dashboard Admin')

@section('content_header')
    <h1>Dashboard Admin</h1>
@stop

@section('content')
<div class="row">
    {{-- Statistik Booking --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalBookingHariIni }}</h3>
                <p>Booking Hari Ini</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalBookingBulanIni }}</h3>
                <p>Booking Bulan Ini</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalBookingTahunIni }}</h3>
                <p>Booking Tahun Ini</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalUser }}</h3>
                <p>User Terdaftar</p>
            </div>
        </div>
    </div>
</div>

{{-- Statistik Booking per Status --}}
<div class="row">
    @foreach(['Pending','Proses','Selesai','Batal'] as $status)
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-calendar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ $status }}</span>
                    <span class="info-box-number">{{ $bookingPerStatus[$status] ?? 0 }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Grafik Tren Booking --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Grafik Booking</h3>
        <div>
            <select id="chartPeriod" class="form-select d-inline" style="width: 140px;">
                <option value="7">7 Hari Terakhir</option>
                <option value="30">1 Bulan Terakhir</option>
                <option value="365">1 Tahun Terakhir</option>
            </select>
            <select id="chartType" class="form-select d-inline" style="width: 120px;">
                <option value="bar">Grafik Batang</option>
                <option value="line">Grafik Garis</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <canvas id="bookingChart" height="100"></canvas>
    </div>
</div>

{{-- Statistik Lainnya --}}
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header"><b>Jam Booking Paling Ramai</b></div>
            <div class="card-body">
                {{ $jamRamai ? $jamRamai->jamBooking . " (" . $jamRamai->total . " booking)" : '-' }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header"><b>Jenis Kucing Terfavorit</b></div>
            <div class="card-body">
                {{ $jenisFavorit ? $jenisFavorit->jenis . " (" . $jenisFavorit->total . " kucing)" : '-' }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header"><b>Layanan Terpopuler</b></div>
            <div class="card-body">
                {{ $layananPopuler ? $layananPopuler->nama_layanan . " (" . $layananPopuler->booking_kucings_count . " booking)" : '-' }}
            </div>
        </div>
    </div>
</div>

{{-- Pendapatan --}}
<div class="card mb-0">
    <div class="card-header"><b>Total Pendapatan</b></div>
    <div class="card-body">
        <h4>Rp {{ number_format($totalPendapatan,0,',','.') }}</h4>
        <ul>
            @foreach($pendapatanPerLayanan as $nama => $nominal)
                <li>{{ $nama }}: Rp {{ number_format($nominal,0,',','.') }}</li>
            @endforeach
        </ul>
    </div>
    <div class="card-footer text-center">
        <strong>&copy; {{ date('Y') }} Aozora.</strong> All rights reserved.
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.initBookingChart(
            {!! json_encode($labels7 ?? $labels) !!},
            {!! json_encode($labels30 ?? $labels) !!},
            {!! json_encode($labels365 ?? $labels) !!},
            {!! json_encode($data7 ?? $data) !!},
            {!! json_encode($data30 ?? $data) !!},
            {!! json_encode($data365 ?? $data) !!}
        );
    });
</script>
@stop