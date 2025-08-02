@extends('adminlte::page')
@section('title', 'Konfirmasi Selesai Booking')
@section('content_header')
    <h1>Konfirmasi Selesai Booking #{{ $booking->id }}</h1>
@stop

@section('content')
<a href="{{ route('admin.booking.by-date', ['tanggal' => $booking->tanggalBooking]) }}" class="btn btn-secondary mb-3">Kembali ke List</a>
<form method="POST" action="{{ route('admin.booking.konfirmasiSelesai', $booking->id) }}">
    @csrf
    <div class="card">
        <div class="card-header">Daftar Kucing yang Sudah Digrooming</div>
        <div class="card-body">
            @foreach($booking->kucings as $kucing)
                <div class="form-group mb-4">
                    <div style="display:flex;align-items:center;">
                        <img src="{{ asset('storage/kucing_images/' . basename($kucing->gambar)) }}"  alt="{{ $kucing->nama_kucing }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;margin-right:12px;">
                        <div>
                            <label><strong>{{ $kucing->nama_kucing }}</strong></label><br>
                            Jenis: {{ $kucing->jenis }}<br>
                            Umur: {{ $kucing->umur }} tahun
                        </div>
                    </div>
                    <textarea name="catatan_{{ $kucing->pivot->kucing_id }}" class="form-control mt-2" placeholder="Catatan/overview (opsional)">{{ $kucing->pivot->catatan ?? '' }}</textarea>
                </div>
            @endforeach
        </div>
    </div>
    <button type="submit" class="btn btn-success mt-3">Konfirmasi Selesai</button>
</form>
@stop