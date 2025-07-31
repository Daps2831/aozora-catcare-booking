@extends('layouts.app')

@section('title', 'Jadwal Booking')

@section('content')
<div class="container" style="max-width: 900px; margin: 40px auto; text-align: center;">
    <h2>Pilih Tanggal Booking</h2>
    <p>Pilih tanggal yang tersedia di kalender. Tanggal sebelum hari ini dinonaktifkan dan tanggal yang sudah penuh (10 kucing) akan dinonaktifkan.</p>

    <div id="calendar"
        data-full-dates='@json($fullDates ?? [])'
        data-events='@json($events ?? [])'></div>
    <div id="calendar-time-info" style="margin-top:1rem;font-weight:bold"></div>
    <button id="btn-konfirmasi-booking" style="margin-top:1.5rem;" disabled>Konfirmasi</button>
    <a href="{{ route('user.dashboard') }}" class="btn-back" style="margin-top:1rem; display: inline-block;">Kembali ke dashboard</a>
</div>
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.css" rel="stylesheet" />
<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
    }
</style>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js"></script>
@endsection