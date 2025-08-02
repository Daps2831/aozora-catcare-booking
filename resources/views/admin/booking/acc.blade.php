@extends('adminlte::page')
@section('title', 'ACC Booking')
@section('content_header')
    <h1>ACC Booking #{{ $booking->id }}</h1>
@stop

@if($errors->has('tim_id'))
    <div class="alert alert-danger">{{ $errors->first('tim_id') }}</div>
@endif

@section('content')
<a href="{{ route('admin.bookings') }}" class="btn btn-secondary mb-3">Kembali ke Kalender</a>
<a href="{{ route('admin.booking.by-date', ['tanggal' => $booking->tanggalBooking]) }}" class="btn btn-light mb-3">Kembali ke List</a>
<div class="card">
    <div class="card-body">
        <form method="GET" action="">
            <div class="form-group">
                <input type="checkbox" id="hide_busy" name="hide_busy" value="1" {{ request('hide_busy') ? 'checked' : '' }} onchange="this.form.submit()">
                <label for="hide_busy">Jangan tampilkan tim di jam yang sama(bentrok)</label>
            </div>
        </form>
        <form method="POST" action="{{ route('admin.booking.accProses', $booking->id) }}">
            @csrf
            <div class="form-group">
                <label for="tim_id">Pilih Tim Groomer</label>
                <select name="tim_id" id="tim_id" class="form-control" required>
                    <option value="">-- Pilih Tim --</option>
                    @foreach($timList as $tim)
                        @php
                            $isBusy = isset($busyTimIds) && $busyTimIds->contains($tim->id_tim);
                            $bentrokBooking = null;
                            if ($isBusy && isset($busyBookings)) {
                                $bentrokBooking = $busyBookings->firstWhere('tim_id', $tim->id_tim);
                            }
                        @endphp
                        <option value="{{ $tim->id_tim }}"
                            @if($isBusy && !request('hide_busy')) style="background:#ffeaea;color:#d00;" @endif
                        >
                            {{ $tim->nama_tim }}
                            @if($isBusy && !request('hide_busy'))
                                (Bentrok jadwal
                                @if($bentrokBooking)
                                    : {{ \Carbon\Carbon::parse($bentrokBooking->jamBooking)->format('H:i') }}
                                    - {{ \Carbon\Carbon::parse($bentrokBooking->jamBooking)->addMinutes($bentrokBooking->estimasi ?? 90)->format('H:i') }}
                                @endif
                                )
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">ACC & Proses</button>
        </form>
    </div>
</div>
@stop