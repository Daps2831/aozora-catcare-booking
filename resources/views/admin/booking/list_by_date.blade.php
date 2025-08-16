
@extends('adminlte::page')
@section('title', 'Daftar Booking Tanggal ' . $tanggal)
@section('content_header')
    <h1>Daftar Booking Tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</h1>
@stop

@section('content')
<a href="{{ route('admin.bookings') }}" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left"></i> Kembali ke Kalender
</a>

<!-- TABLE WITH RESPONSIVE WRAPPER  -->
<div class="table-responsive-wrapper">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th style="min-width: 60px;">ID</th>
                <th style="min-width: 150px;">Customer</th>
                <th style="min-width: 120px;">Kontak</th>
                <th style="min-width: 150px;">Kucing</th>
                <th style="min-width: 200px;">Layanan</th>
                <th style="min-width: 120px;">Jam</th>
                <th style="min-width: 100px;">Status</th>
                <th style="min-width: 100px;">Tim</th>
                <th style="min-width: 200px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $b)
            <tr class="{{ strtolower($b->statusBooking) == 'batal' ? 'booking-row-batal' : '' }}">
                <td>
                    <span class="badge bg-primary">{{ $b->id }}</span>
                </td>
                <td>
                    <div class="customer-info">
                        <strong>{{ $b->customer->name }}</strong>
                        @if($b->customer->email)
                            <br><small class="text-muted">{{ $b->customer->email }}</small>
                        @endif
                    </div>
                </td>
                <td>
                    <span class="text-nowrap">
                        {{ $b->customer->kontak ?? '-' }}
                    </span>
                </td>
                <td>
                    <div class="kucing-list">
                        @foreach($b->kucings as $kucing)
                            <div class="kucing-item">
                                <i class="fas fa-cat text-secondary"></i>
                                <span>{{ $kucing->nama_kucing }}</span>
                                @if($kucing->jenis_kucing)
                                    <small class="text-muted">({{ $kucing->jenis_kucing }})</small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </td>
                <td>
                    <div class="layanan-list">
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
                            <div class="layanan-item">
                                <i class="fas fa-scissors text-info"></i>
                                <span>{{ $layananNama }}</span>
                            </div>
                        @endforeach
                    </div>
                </td>
                <td>
                    @php
                        $start = \Carbon\Carbon::parse($b->tanggalBooking . ' ' . $b->jamBooking);
                        $end = $start->copy()->addMinutes($b->estimasi ?? 90);
                    @endphp
                    <div class="time-info">
                        <i class="fas fa-clock text-secondary"></i>
                        <span class="text-nowrap">{{ $start->format('H:i') }} - {{ $end->format('H:i') }}</span>
                        <br><small class="text-muted">{{ $b->estimasi ?? 90 }} menit</small>
                    </div>
                </td>
                <td>
                    @php
                        $statusText = $b->statusBooking;
                        $statusClass = '';
                        switch(strtolower($statusText)) {
                            case 'pending':
                                $statusClass = 'status-pending';
                                break;
                            case 'proses':
                                $statusClass = 'status-proses';
                                break;
                            case 'selesai':
                                $statusClass = 'status-selesai';
                                break;
                            case 'batal':
                                $statusClass = 'status-batal';
                                break;
                            default:
                                $statusClass = 'status-pending';
                        }
                    @endphp
                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                </td>
                <td>
                    @if($b->tim)
                        <div class="tim-info">
                            <i class="fas fa-users text-success"></i>
                            <span>{{ $b->tim->nama_tim }}</span>
                        </div>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <div class="action-buttons">
                        @if($b->statusBooking == 'Pending')
                            <a href="{{ route('admin.booking.acc', $b->id) }}" class="btn btn-success btn-sm mb-1" title="ACC Booking">
                                <i class="fas fa-check"></i> ACC
                            </a>
                            <a href="{{ url('/admin/booking/' . $b->id . '/edit') }}" class="btn btn-warning btn-sm mb-1" title="Edit Booking">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @elseif($b->statusBooking == 'Proses')
                            <form action="{{ route('admin.booking.selesai', $b->id) }}" method="GET" style="display:inline;" class="mb-1">
                                <button class="btn btn-success btn-sm" type="submit" title="Tandai Selesai">
                                    <i class="fas fa-check-circle"></i> Selesai
                                </button>
                            </form>
                            <form action="{{ route('admin.booking.batalAcc', $b->id) }}" method="POST" style="display:inline;" class="mb-1">
                                @csrf
                                <button class="btn btn-secondary btn-sm" type="submit" onclick="return confirm('Batalkan ACC dan kembalikan ke pending?')" title="Batal ACC">
                                    <i class="fas fa-undo"></i> Batal ACC
                                </button>
                            </form>
                        @endif
                        <a href="{{ url('/admin/booking/' . $b->id) }}" class="btn btn-info btn-sm mb-1" title="Lihat Detail">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <form action="{{ url('/admin/booking/' . $b->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Yakin hapus booking ini?')" title="Hapus Booking">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada booking pada tanggal ini</h5>
                        <p class="text-muted">Belum ada booking yang terdaftar untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</p>
                        <a href="{{ route('admin.bookings') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Kalender
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Summary Info -->
@if($bookings->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-calendar-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Booking</span>
                <span class="info-box-number">{{ $bookings->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number">{{ $bookings->where('statusBooking', 'Pending')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-cog"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Proses</span>
                <span class="info-box-number">{{ $bookings->where('statusBooking', 'Proses')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Selesai</span>
                <span class="info-box-number">{{ $bookings->where('statusBooking', 'Selesai')->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endif
@stop

@section('css')
<style>
    /* ============================================= */
    /* TABLE RESPONSIVE   */
    /* ============================================= */
    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        border: 1px solid #ddd;
    }

    .table-responsive-wrapper::-webkit-scrollbar {
        height: 6px;
    }

    .table-responsive-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .table-responsive-wrapper::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    .table-responsive-wrapper::-webkit-scrollbar-thumb:hover {
        background: #aaa;
    }

    /* Enhanced table styling */
    .table-responsive-wrapper .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 800px; /* Ensure minimum width for proper layout */
    }

    .table-responsive-wrapper .table thead th {
        background: #343a40;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 10;
        padding: 12px 8px;
        text-align: center;
        vertical-align: middle;
    }

    .table-responsive-wrapper .table tbody td {
        padding: 12px 8px;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }

    .table-responsive-wrapper .table tbody tr:hover {
        background: #f8f9fa;
    }

    /* ============================================= */
    /* STATUS STYLES */
    /* ============================================= */
    .status-pending {
        background: #fff3cd;
        color: #856404;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #ffc107;
        display: inline-block;
    }

    .status-proses {
        background: #d1ecf1;
        color: #0c5460;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #17a2b8;
        display: inline-block;
    }

    .status-selesai {
        background: #d4edda;
        color: #155724;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #28a745;
        display: inline-block;
    }

    .status-batal {
        background: #f8d7da;
        color: #721c24;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #dc3545;
        text-decoration: line-through;
        display: inline-block;
    }

    .booking-row-batal {
        background: rgba(248, 215, 218, 0.3);
        opacity: 0.8;
    }

    .booking-row-batal td {
        color: #721c24;
    }

    /* ============================================= */
    /* CONTENT STYLING */
    /* ============================================= */
    .customer-info strong {
        color: #495057;
        font-size: 14px;
    }

    .customer-info small {
        font-size: 11px;
    }

    .kucing-list .kucing-item {
        margin-bottom: 2px;
        font-size: 13px;
    }

    .kucing-list .kucing-item i {
        width: 16px;
        margin-right: 4px;
    }

    .layanan-list .layanan-item {
        margin-bottom: 2px;
        font-size: 13px;
    }

    .layanan-list .layanan-item i {
        width: 16px;
        margin-right: 4px;
    }

    .time-info {
        font-size: 13px;
    }

    .time-info i {
        width: 16px;
        margin-right: 4px;
    }

    .tim-info {
        font-size: 13px;
    }

    .tim-info i {
        width: 16px;
        margin-right: 4px;
    }

    /* ============================================= */
    /* ACTION BUTTONS */
    /* ============================================= */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        min-width: 200px;
    }

    .action-buttons .btn {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 4px;
        white-space: nowrap;
    }

    .action-buttons .btn i {
        margin-right: 2px;
    }

    /* ============================================= */
    /* MOBILE RESPONSIVE */
    /* ============================================= */
    @media (max-width: 768px) {
        .table-responsive-wrapper .table {
            min-width: 900px; /* Increase for mobile scroll */
        }
        
        .table-responsive-wrapper .table thead th {
            padding: 8px 6px;
            font-size: 12px;
        }
        
        .table-responsive-wrapper .table tbody td {
            padding: 8px 6px;
            font-size: 12px;
        }
        
        .action-buttons {
            flex-direction: column;
            min-width: 120px;
        }
        
        .action-buttons .btn {
            font-size: 10px;
            padding: 3px 6px;
            margin-bottom: 2px;
        }
        
        .customer-info,
        .kucing-list,
        .layanan-list,
        .time-info,
        .tim-info {
            font-size: 11px;
        }
    }

    @media (max-width: 480px) {
        .table-responsive-wrapper .table {
            min-width: 1000px; /* Even more width for small mobile */
        }
        
        .table-responsive-wrapper .table thead th {
            padding: 6px 4px;
            font-size: 11px;
        }
        
        .table-responsive-wrapper .table tbody td {
            padding: 6px 4px;
            font-size: 11px;
        }
    }

    /* ============================================= */
    /* EMPTY STATE */
    /* ============================================= */
    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }

    .empty-state i {
        margin-bottom: 20px;
    }

    /* ============================================= */
    /* INFO BOXES - ADMINLTE STYLE */
    /* ============================================= */
    .info-box {
        display: flex;
        align-items: stretch;
        min-height: 90px;
        background: #fff;
        width: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        border-radius: 2px;
        margin-bottom: 15px;
        overflow: hidden; /* Tambahkan agar icon tidak keluar */
    }

    .info-box .info-box-icon {
        border-radius: 2px 0 0 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 90px;
        width: 90px;
        text-align: center;
        font-size: 45px;
        background: rgba(0,0,0,0.2);
        flex-shrink: 0;
    }

    .info-box .info-box-content {
        padding: 15px 20px;
        margin-left: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 100%;
    }

    .info-box .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 18px;
    }

    .info-box .info-box-text {
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ============================================= */
    /* PRINT STYLES */
    /* ============================================= */
    @media print {
        .table-responsive-wrapper {
            overflow: visible !important;
            box-shadow: none !important;
        }
        
        .table-responsive-wrapper .table {
            min-width: auto !important;
        }
        
        .action-buttons {
            display: none !important;
        }
        
        .info-box {
            break-inside: avoid;
        }
    }
</style>
@stop