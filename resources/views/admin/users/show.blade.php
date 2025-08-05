
{{-- filepath: c:\laragon\www\testing\resources\views\admin\users\show.blade.php --}}
@extends('adminlte::page')
@section('title', 'Detail User')
@section('content_header')
    <h1>Detail User: {{ $user->name }}</h1>
@stop

@section('content')
{{-- Informasi User Account --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user"></i> Informasi Akun
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Role:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge badge-success">Aktif</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Terdaftar Sejak:</strong></td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Update:</strong></td>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Kucing:</strong></td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $user->customer ? $user->customer->kucings->count() : 0 }} kucing
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($user->customer)
{{-- Informasi Customer Detail --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-address-card"></i> Informasi Customer
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-borderless">
                            <tr>
                                <td width="20%"><strong>Nama Lengkap:</strong></td>
                                <td>{{ $user->customer->name ?? 'Belum diisi' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Username:</strong></td>
                                <td>{{ $user->customer->username ?? 'Belum diisi' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email Customer:</strong></td>
                                <td>{{ $user->customer->email ?? 'Belum diisi' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Telepon:</strong></td>
                                <td>{{ $user->customer->kontak ?? 'Belum diisi' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat:</strong></td>
                                <td>{{ $user->customer->alamat ?? 'Belum diisi' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Booking --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-check"></i> Statistik Booking
                </h3>
            </div>
            <div class="card-body">
                @php
                    $bookings = \App\Models\Booking::where('customer_id', $user->customer->id)->get();
                    $totalBooking = $bookings->count();
                    $bookingSelesai = $bookings->where('statusBooking', 'Selesai')->count();
                    $bookingPending = $bookings->where('statusBooking', 'Pending')->count();
                    $bookingDiproses = $bookings->where('statusBooking', 'Diproses')->count();
                @endphp
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Booking</span>
                                <span class="info-box-number">{{ $totalBooking }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Selesai</span>
                                <span class="info-box-number">{{ $bookingSelesai }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending</span>
                                <span class="info-box-number">{{ $bookingPending }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-cogs"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Diproses</span>
                                <span class="info-box-number">{{ $bookingDiproses }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Daftar Kucing --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cat"></i> Daftar Kucing ({{ $user->customer->kucings->count() }})
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.kucing.create', $user->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Kucing
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($user->customer->kucings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="20%">Foto</th>
                                    <th>Nama Kucing</th>
                                    <th>Jenis</th>
                                    <th>Usia</th>
                                    <th>Status Kesehatan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->customer->kucings as $kucing)
                                <tr>
                                    <td class="text-center align-middle">
                                        @if($kucing->gambar)
                                            <img src="{{ asset('storage/kucing_images/' . basename($kucing->gambar)) }}" 
                                                 alt="{{ $kucing->nama_kucing }}" 
                                                 class="img-thumbnail kucing-photo" 
                                                 style="max-height: 120px; width: 120px; object-fit: cover; cursor: pointer;"
                                                 onclick="showImageModal('{{ asset('storage/kucing_images/' . basename($kucing->gambar)) }}', '{{ $kucing->nama_kucing }}')">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 120px; width: 120px; border-radius: 8px; border: 2px dashed #ddd;">
                                                <div class="text-center">
                                                    <i class="fas fa-cat fa-2x text-muted mb-2"></i>
                                                    <br><small class="text-muted">Tidak ada foto</small>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <strong>{{ $kucing->nama_kucing }}</strong>
                                        @if($kucing->riwayat_kesehatan)
                                            <br><small class="text-muted">{{ Str::limit($kucing->riwayat_kesehatan, 50) }}</small>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $kucing->jenis }}</td>
                                    <td class="align-middle">{{ $kucing->umur }} tahun</td>
                                    <td class="align-middle">
                                        @if($kucing->riwayat_kesehatan)
                                            <span class="badge badge-success">Ada Riwayat</span>
                                        @else
                                            <span class="text-muted">Belum ada riwayat</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group-vertical" role="group">
                                            <a href="{{ route('admin.users.kucing.edit', [$user->id, $kucing->id]) }}" 
                                               class="btn btn-warning btn-sm mb-1" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.users.kucing.destroy', [$user->id, $kucing->id]) }}" 
                                                  method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Hapus kucing {{ $kucing->nama_kucing }}?')"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-cat fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada kucing yang terdaftar</p>
                        <a href="{{ route('admin.users.kucing.create', $user->id) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Kucing Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk melihat gambar lebih besar --}}
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Foto Kucing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>

@else
{{-- Jika user belum punya customer profile --}}
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <h5><i class="icon fas fa-info"></i> Informasi!</h5>
            User ini belum melengkapi profil customer. Customer perlu login dan mengisi profil untuk dapat melakukan booking.
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        @if($user->role !== 'admin')
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit User
            </a>
        @endif
    </div>
</div>
@stop

@section('css')
<style>
.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: .25rem;
    background: #fff;
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: .5rem;
}

.info-box .info-box-icon {
    border-radius: .25rem;
    align-items: center;
    display: flex;
    font-size: 1.875rem;
    justify-content: center;
    text-align: center;
    width: 70px;
}

.info-box .info-box-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.8;
    flex: 1;
    padding: 0 10px;
}

.info-box .info-box-number {
    font-size: 18px;
    font-weight: 700;
}

.info-box .info-box-text {
    display: block;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Style untuk foto kucing */
.kucing-photo {
    transition: transform 0.2s ease-in-out;
    border-radius: 8px !important;
}

.kucing-photo:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Table row spacing */
.table td {
    padding: 1rem 0.75rem;
}

/* Button group vertical spacing */
.btn-group-vertical .btn {
    margin-bottom: 0.25rem;
}

.btn-group-vertical .btn:last-child {
    margin-bottom: 0;
}
</style>
@stop

@section('js')
<script>
function showImageModal(imageSrc, imageName) {
    $('#modalImage').attr('src', imageSrc);
    $('#modalImage').attr('alt', imageName);
    $('#imageModalLabel').text('Foto ' + imageName);
    $('#imageModal').modal('show');
}

$(document).ready(function() {
    console.log('User show page loaded');
});
</script>
@stop