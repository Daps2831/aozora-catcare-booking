@extends('layouts.app')

@section('title', 'Buat Booking')

@section('content')
<div class="container" style="max-width: 600px; margin: 40px auto;">
    <h1>Formulir Booking</h1>
    <p>Anda akan membuat booking untuk tanggal: <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</strong></p>

    @if(session('error'))
        <div style="color: red; margin-bottom: 15px;">{{ session('error') }}</div>
    @endif

    <form action="{{ route('booking.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tanggalBooking" value="{{ $selectedDate }}">

        <div class="form-group">
            <label for="layanan_id">Pilih Jenis Layanan</label>
            <select name="layanan_id" id="layanan_id" class="form-control" required>
                <option value="">-- Pilih Layanan --</option>
                @foreach($layanans as $layanan)
                    <option value="{{ $layanan->id }}">{{ $layanan->namaLayanan }} - (Rp {{ number_format($layanan->harga) }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Pilih Kucing (bisa lebih dari satu)</label>
            <div class="checkbox-group" style="border: 1px solid #ccc; padding: 10px; border-radius: 5px; max-height: 150px; overflow-y: auto;">
                @forelse($kucings as $kucing)
                    <div>
                        <input type="checkbox" name="kucing_ids[]" value="{{ $kucing->id }}" id="kucing_{{ $kucing->id }}">
                        <label for="kucing_{{ $kucing->id }}">{{ $kucing->nama_kucing }} (Jenis: {{ $kucing->jenis }})</label>
                    </div>
                @empty
                    <p>Anda belum memiliki data kucing. <a href="{{ route('kucing.register') }}">Daftarkan kucing sekarang</a>.</p>
                @endforelse
            </div>
        </div>

        <button type="submit" class="cta-btn-diteks" style="width: 100%; max-width: none;">Kirim Booking</button>
    </form>
</div>
@endsection