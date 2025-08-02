@extends('adminlte::page')
@section('title', 'Detail Booking')
@section('content_header')
    <h1>Detail Booking #{{ $booking->id }}</h1>
@stop

@section('content')
<a href="{{ route('admin.bookings') }}" class="btn btn-secondary mb-3">Kembali ke Kalender</a>
<a href="{{ url()->previous() }}" class="btn btn-light mb-3">Kembali ke List</a>
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $booking->id }}</td>
            </tr>
            <tr>
                <th>Customer</th>
                <td>{{ $booking->customer->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Kontak</th>
                <td>{{ $booking->customer->kontak ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ \Carbon\Carbon::parse($booking->tanggalBooking)->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Jam</th>
                <td>
                    @php
                        $start = \Carbon\Carbon::parse($booking->tanggalBooking . ' ' . $booking->jamBooking);
                        $end = $start->copy()->addMinutes($booking->estimasi ?? 90);
                    @endphp
                    {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td><span class="badge badge-{{ strtolower($booking->statusBooking) }}">{{ $booking->statusBooking }}</span></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $booking->alamatBooking }}</td>
            </tr>
            <tr>
                <th>Kucing & Layanan</th>
                <td>
                    <ul>
                        @foreach($booking->kucings as $kucing)
                            <li style="margin-bottom:16px;list-style:none;">
                                <div style="display:flex;align-items:center;">
                                    <img src="{{ asset('storage/kucing_images/' . basename($kucing->gambar)) }}" alt="{{ $kucing->nama_kucing }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;margin-right:12px;">
                                    <div>
                                        <strong>{{ $kucing->nama_kucing }}</strong> <br>
                                        Jenis: {{ $kucing->jenis }} <br>
                                        Umur: {{ $kucing->umur }} tahun <br>
                                        @php
                                            $layananNama = '-';
                                            if ($kucing->pivot->layanan_id) {
                                                $layananModel = \App\Models\Layanan::find($kucing->pivot->layanan_id);
                                                if ($layananModel) {
                                                    $layananNama = $layananModel->nama_layanan;
                                                }
                                            }
                                        @endphp
                                        Layanan: {{ $layananNama }}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </table>
    </div>
</div>
@stop