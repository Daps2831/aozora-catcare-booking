@extends('adminlte::page')
@section('title', 'Kelola Booking')
@section('content_header')
    <h1>Kalender Booking</h1>
@stop

@section('content')
<div id="calendar"
    data-events='@json($events ?? [])'></div>

<hr>

<!-- Fitur Pencarian & Filter -->
<form method="GET" class="mb-4" action="{{ route('admin.bookings') }}">
    <div class="row">
        <div class="col-md-3 mb-2">
            <input type="text" name="q" class="form-control" placeholder="Cari customer, kucing, layanan..." value="{{ request('q') }}">
        </div>
        <div class="col-md-2 mb-2">
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Batal" {{ request('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-2 mb-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-2 mb-2">
            <a href="{{ route('admin.bookings') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2 d-flex align-items-center">
            <input type="checkbox" name="filter_calendar" id="filter_calendar" value="1" {{ request('filter_calendar') ? 'checked' : '' }}>
            <label for="filter_calendar" class="ms-2 mb-0">Terapkan filter ke kalender</label>
        </div>
    </div>
</form>

<h3>Daftar Booking</h3>
<div class="d-flex justify-content-between align-items-center mb-2">
    <div>
        <form method="GET" action="{{ route('admin.bookings') }}" class="d-inline">
            @foreach(request()->except('per_page') as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <span class="me-2">Tampilkan Baris Booking per Halaman</span>
            <select name="per_page" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
        </form>
    </div>
    <div>
        {{ $bookings->links() }}
    </div>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Kontak</th>
            <th>Kucing</th>
            <th>Layanan</th>
            <th>Tanggal</th> <!-- Tambahkan kolom ini -->
            <th>Jam</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bookings as $b)
        <tr>
            <td>{{ $b->id }}</td>
            <td>{{ $b->customer->name ?? '-' }}</td>
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
             <td>{{ $b->tanggalBooking }}</td> <!-- Tampilkan tanggal booking -->
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
                    if (strtolower($statusText) == 'pending') $statusColor = 'color:#f1c40f;';
                    elseif (strtolower($statusText) == 'proses') $statusColor = 'color:#3498db;';
                    elseif (strtolower($statusText) == 'selesai') $statusColor = 'color:#27ae60;';
                @endphp
                <span style="{{ $statusColor }}">{{ $statusText }}</span>
            </td>
            <td>
                @if($b->statusBooking == 'Pending')
                    <a href="{{ route('admin.booking.acc', $b->id) }}" class="btn btn-success btn-sm">ACC</a>
                    <a href="{{ route('admin.booking.edit', $b->id) }}" class="btn btn-warning btn-sm">Edit</a>
                @elseif($b->statusBooking == 'Proses')
                    <form action="{{ route('admin.booking.selesai', $b->id) }}" method="GET" style="display:inline;">
                        <button class="btn btn-success btn-sm" type="submit">Selesai</button>
                    </form>
                    <form action="{{ route('admin.booking.batalAcc', $b->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Batalkan ACC dan kembalikan ke pending?')">Batal ACC</button>
                    </form>
                @endif
                <a href="{{ route('admin.booking.show', $b->id) }}" class="btn btn-info btn-sm">Lihat</a>
                <form action="{{ route('admin.booking.destroy', $b->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">Tidak ada booking.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $bookings->links() }}
@stop

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
    .fc-daygrid-day:hover {
        background: #eaf1fb !important;
        cursor: pointer;
        transition: background 0.2s;
    }
    .fc-daygrid-day.active-date {
        background: #3498db !important;
        color: #fff !important;
        transition: background 0.2s;
    }
    .fc-event,
    .fc-daygrid-event,
    .fc-daygrid-dot-event {
        pointer-events: none !important;
    }
</style>
@stop



@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    initFullCalendarAdmin();
});
</script>
@endsection