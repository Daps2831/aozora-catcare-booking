
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
                <th width="20%">ID</th>
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
                <td>
                    <span class="badge badge-{{ 
                        $booking->statusBooking == 'Pending' ? 'warning' : 
                        ($booking->statusBooking == 'Proses' ? 'info' : 
                        ($booking->statusBooking == 'Batal' || $booking->statusBooking == 'Dibatalkan' ? 'danger' : 'success')) 
                    }}">
                        {{ $booking->statusBooking }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Tim Groomer</th>
                <td>{{ $booking->tim->nama_tim ?? '-' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $booking->alamatBooking }}</td>
            </tr>
        </table>
    </div>
</div>

{{-- Informasi Kucing & Layanan --}}
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-cat"></i> 
            Kucing & Layanan 
            @if($booking->statusBooking == 'Selesai')
                <span class="badge badge-light ml-2">Dengan Catatan Grooming</span>
            @endif
        </h5>
    </div>
    <div class="card-body">
        @if($booking->kucings->count() > 0)
            <div class="row">
                @foreach($booking->kucings as $index => $kucing)
                    <div class="col-md-6 mb-4">
                        <div class  ="card border-left-primary">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    {{-- Gambar Kucing --}}
                                    @if($kucing->gambar && file_exists(storage_path('app/public/' . $kucing->gambar)))
                                        <img src="{{ asset('storage/' . $kucing->gambar) }}" 
                                             alt="{{ $kucing->nama_kucing }}" 
                                             class="rounded-circle mr-3"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle mr-3 d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px;">
                                            <i class="fas fa-cat text-white fa-2x"></i>
                                        </div>
                                    @endif
                                    
                                    {{-- Info Kucing --}}
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1">{{ $kucing->nama_kucing }}</h5>
                                        <p class="card-text text-muted mb-0">
                                            <small>
                                                <i class="fas fa-paw"></i> {{ $kucing->jenis }} |
                                                <i class="fas fa-birthday-cake"></i> {{ $kucing->umur }} tahun
                                            </small>
                                        </p>
                                    </div>
                                </div>
                                
                                {{-- Layanan --}}
                                <div class="mb-3">
                                    @php
                                        $layananNama = '-';
                                        $layananHarga = 0;
                                        if ($kucing->pivot->layanan_id) {
                                            $layananModel = \App\Models\Layanan::find($kucing->pivot->layanan_id);
                                            if ($layananModel) {
                                                $layananNama = $layananModel->nama_layanan;
                                                $layananHarga = $layananModel->harga;
                                            }
                                        }
                                    @endphp
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge badge-info">
                                            <i class="fas fa-cut"></i> {{ $layananNama }}
                                        </span>
                                        @if($layananHarga > 0)
                                            <span class="font-weight-bold text-success">
                                                Rp {{ number_format($layananHarga, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Catatan Grooming (Hanya tampil jika status Selesai) --}}
                                @if($booking->statusBooking == 'Selesai')
                                    <div class="border-top pt-3">
                                        <h6 class="text-primary">
                                            <i class="fas fa-clipboard-list"></i> Catatan Grooming:
                                        </h6>
                                        @if($kucing->pivot->catatan)
                                            <div class="alert alert-light border-left-success">
                                                <i class="fas fa-quote-left text-muted"></i>
                                                <em>{{ $kucing->pivot->catatan }}</em>
                                                <i class="fas fa-quote-right text-muted"></i>
                                            </div>
                                        @else
                                            <p class="text-muted font-italic">
                                                <i class="fas fa-info-circle"></i> Tidak ada catatan khusus
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Tidak ada kucing terdaftar dalam booking ini.
            </div>
        @endif
    </div>
</div>

{{-- Ringkasan Total (untuk booking selesai) --}}
@if($booking->statusBooking == 'Selesai')
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-calculator"></i> Ringkasan Pembayaran
            </h5>
        </div>
        <div class="card-body">
            @php
                $totalHarga = 0;
                foreach($booking->kucings as $kucing) {
                    if ($kucing->pivot->layanan_id) {
                        $layanan = \App\Models\Layanan::find($kucing->pivot->layanan_id);
                        if ($layanan) {
                            $totalHarga += $layanan->harga;
                        }
                    }
                }
            @endphp
            
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-sm">
                        @foreach($booking->kucings as $kucing)
                            @php
                                $layanan = \App\Models\Layanan::find($kucing->pivot->layanan_id);
                            @endphp
                            <tr>
                                <td>{{ $kucing->nama_kucing }}</td>
                                <td>{{ $layanan->nama_layanan ?? '-' }}</td>
                                <td class="text-right">
                                    Rp {{ number_format($layanan->harga ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h4>Total Pembayaran</h4>
                            <h2 class="text-success">
                                Rp {{ number_format($totalHarga, 0, ',', '.') }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@stop

@section('css')
<style>
.border-left-primary {
    border-left: 4px solid #007bff !important;
}

.border-left-success {
    border-left: 4px solid #28a745 !important;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.alert-light {
    background-color: #fafafa;
    border: 1px solid #e9ecef;
}

.badge {
    font-size: 0.9em;
}

.badge-sm {
    font-size: 0.75em;
    padding: 0.25rem 0.5rem;
}

/* Custom styling untuk kucing cards */
.kucing-card {
    transition: transform 0.2s;
}

.kucing-card:hover {
    transform: translateY(-2px);
}

/* Quote styling */
.fas.fa-quote-left,
.fas.fa-quote-right {
    font-size: 0.8em;
    opacity: 0.6;
}

/* Total payment card */
.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@stop