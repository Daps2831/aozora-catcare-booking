@extends('adminlte::page')
@section('title', 'Daftar Booking Tanggal ' . $tanggal)
@section('content_header')
    <h1>Daftar Booking Tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</h1>
@stop

@section('content')
<a href="{{ route('admin.bookings') }}" class="btn btn-secondary mb-3">Kembali ke Kalender</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Kontak</th>
            <th>Kucing</th>
            <th>Layanan</th>
            <th>Jam</th>
            <th>Status</th>
            <th>Tim</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $b)
        <tr>
            <td>{{ $b->id }}</td>
            <td>{{ $b->customer->name }}</td>
            <td>{{ $b->customer->kontak ?? '-' }}</td>
            <td>{{ $b->kucings->pluck('nama_kucing')->join(', ') }}</td>
            <td>
                @foreach($b->kucings as $kucing)
                    @php
                        $layananNama = '-';
                        if ($kucing->pivot->layanan_id) {
                            $layananModel = \App\Models\Layanan::find($kucing->pivot->layanan_id);
                            if ($layananModel) {
                                $layananNama = $layananModel->nama_layanan;
                            }
                        }
                    @endphp
                    {{ $layananNama }}
                    @if(!$loop->last), @endif
                @endforeach
            </td>
            <td>
                @php
                    $start = \Carbon\Carbon::parse($b->tanggalBooking . ' ' . $b->jamBooking);
                    $end = $start->copy()->addMinutes($b->estimasi ?? 90);
                @endphp
                {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
            </td>
            <td>
                @php
                    $statusText = $b->statusBooking;
                    $statusColor = '';
                    if (strtolower($statusText) == 'pending') $statusColor = 'color:#f1c40f;'; // kuning
                    elseif (strtolower($statusText) == 'proses') $statusColor = 'color:#3498db;'; // biru
                    elseif (strtolower($statusText) == 'selesai') $statusColor = 'color:#27ae60;'; // hijau
                @endphp
                <span style="{{ $statusColor }}">{{ $statusText }}</span>
            </td>
            <td>
                {{ $b->tim ? $b->tim->nama_tim : '-' }}
            </td>
            <td>
                @if($b->statusBooking == 'Pending')
                    <a href="{{ route('admin.booking.acc', $b->id) }}" class="btn btn-success btn-sm">ACC</a>
                    <a href="{{ url('/admin/booking/' . $b->id . '/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                @elseif($b->statusBooking == 'Proses')
                    <form action="{{ route('admin.booking.selesai', $b->id) }}" method="GET" style="display:inline;">
                        <button class="btn btn-success btn-sm" type="submit">Selesai</button>
                    </form>
                    <form action="{{ route('admin.booking.batalAcc', $b->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Batalkan ACC dan kembalikan ke pending?')">Batal ACC</button>
                    </form>
                @endif
                <a href="{{ url('/admin/booking/' . $b->id) }}" class="btn btn-info btn-sm">Lihat</a>
                <form action="{{ url('/admin/booking/' . $b->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop