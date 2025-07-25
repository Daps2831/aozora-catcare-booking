@extends('layouts.app')

@section('title', 'Jadwal Booking')

@section('content')
<div class="container" style="max-width: 900px; margin: 40px auto; text-align: center;">
    <h2>Pilih Tanggal Booking</h2>
    <p>Pilih tanggal yang tersedia di kalender.Tanggal sebelum hari ini dinonaktifkan dan Tanggal yang sudah penuh (10 kucing) akan dinonaktifkan.</p>

    <div id="my-calendar"></div>
    <div id="calendar-time-info" style="margin-top:1rem;font-weight:bold"></div>
    <button id="btn-konfirmasi-booking" style="margin-top:1.5rem;"disabled>Konfirmasi</button>
    <a href="{{ route('user.dashboard') }}" class="btn-back" style="margin-top:1rem; display: inline-block;">Kembali ke dashboard</a>
</div>



@endsection

{{-- Tidak perlu ada @section('scripts') lagi di sini --}}