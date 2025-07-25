
@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
<div class="container" style="max-width: 800px; margin: 40px auto;">
    <h2>Riwayat Booking Anda</h2>
    <hr>
    @if($riwayatBookings->isEmpty())
        <p>Belum ada riwayat booking.</p>
    @else
        <div class="booking-list">
            @foreach($riwayatBookings as $booking)
                <div class="booking-item" style="border-bottom:1px solid #eee;padding:1rem 0;">
                    <strong>{{ $booking->layanan->nama_layanan ?? '-' }}</strong>
                    <br>
                    Tanggal: {{ \Carbon\Carbon::parse($booking->tanggalBooking)->format('d F Y') }}
                    <br>
                    Jam: {{ $booking->jamBooking ?? '-' }}
                    <br>
                    Kucing: {{ $booking->kucings->pluck('nama_kucing')->join(', ') }}
                    <br>
                    Status: <span style="color:{{ $booking->status == 'selesai' ? 'green' : 'orange' }}">{{ ucfirst($booking->status) }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection