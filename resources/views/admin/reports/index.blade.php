@extends('adminlte::page')
@section('title', 'Laporan Admin')

@section('content_header')
    <h1>Laporan Admin</h1>
@stop

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <strong>Jumlah Tim Grooming</strong>
                <div>{{ $jumlahTim }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <strong>Jumlah Groomer</strong>
                <div>{{ $jumlahGroomer }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <strong>Jumlah Booking Grooming</strong>
                <div>{{ $jumlahBooking }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <strong>Jumlah Pelanggan</strong>
                <div>{{ $jumlahPelanggan }}</div>
            </div>
        </div>
    </div>
</div>

<h4>Laporan Tim Grooming</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Tim</th>
            <th>Anggota</th>
            <th>Jumlah Order</th>
        </tr>
    </thead>
    <tbody>
        @foreach($timList as $tim)
        <tr>
            <td>{{ $tim->nama_tim }}</td>
            <td>
                {{ $tim->anggota1->nama ?? '-' }}
                @if($tim->anggota2)
                    , {{ $tim->anggota2->nama }}
                @endif
            </td>
            <td>{{ $tim->bookings->count() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h4>Laporan Groomer</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Groomer</th>
            <th>Tim</th>
            <th>Jumlah Order</th>
        </tr>
    </thead>
    <tbody>
        @foreach($groomerList as $groomer)
        <tr>
            <td>{{ $groomer->nama }}</td>
            <td>
                @php
                    $tim = \App\Models\TimGroomer::where('anggota_1', $groomer->id_groomer)
                        ->orWhere('anggota_2', $groomer->id_groomer)
                        ->first();
                @endphp
                {{ $tim->nama_tim ?? '-' }}
            </td>
            @php
                $bookingCount = \App\Models\Booking::whereHas('tim', function($q) use ($groomer) {
                    $q->where('anggota_1', $groomer->id_groomer)
                    ->orWhere('anggota_2', $groomer->id_groomer);
                })->count();
            @endphp
            <td>{{ $bookingCount }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h4>Laporan Order Grooming</h4>
<form method="GET" class="mb-2">
    <div class="row">
        <div class="col-md-3">
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Batal" {{ request('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="tim_id" class="form-control">
                <option value="">Semua Tim</option>
                @foreach($timList as $tim)
                    <option value="{{ $tim->id }}" {{ request('tim_id') == $tim->id ? 'selected' : '' }}>{{ $tim->nama_tim }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Status</th>
            <th>Tim</th>
            <th>Groomer</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orderList as $order)
        <tr>
            <td>{{ $order->tanggalBooking }}</td>
            <td>{{ $order->customer->name ?? '-' }}</td>
            <td>{{ $order->statusBooking }}</td>
            <td>{{ $order->tim->nama_tim ?? '-' }}</td>
            <td>
                @if($order->tim)
                    {{ $order->tim->anggota1->nama ?? '-' }}
                    @if($order->tim->anggota2)
                        , {{ $order->tim->anggota2->nama }}
                    @endif
                @else
                    -
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $orderList->links() }}

<h4>Grafik Order Grooming</h4>
<div>
    <!-- Contoh chart sederhana, bisa pakai Chart.js -->
    <canvas id="grafikOrder"></canvas>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('grafikOrder').getContext('2d');
    var grafikOrder = @json(array_values($grafikOrder));
    var labels = @json($grafikLabels);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{    
                label: 'Order per Bulan',
                data: grafikOrder,
                backgroundColor: '#3498db'
            }]
        }
    });
</script>
@endsection