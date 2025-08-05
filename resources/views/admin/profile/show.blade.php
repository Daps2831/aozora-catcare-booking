
@extends('adminlte::page')
@section('title', 'Profil Saya')
@section('content_header')
    <h1>Profil Saya</h1>
@stop

@section('content')
{{-- Alert Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- Informasi Profil --}}
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user"></i> Informasi Profil
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <td width="30%"><strong>Nama Lengkap:</strong></td>
                                <td>{{ auth()->user()->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Username:</strong></td>
                                <td>{{ auth()->user()->username }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ auth()->user()->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Role:</strong></td>
                                <td>
                                    <span class="badge badge-danger">
                                        {{ ucfirst(auth()->user()->role) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Terdaftar Sejak:</strong></td>
                                <td>{{ auth()->user()->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Update:</strong></td>
                                <td>{{ auth()->user()->updated_at->format('d F Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cogs"></i> Aksi Cepat
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-primary btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                    <button type="button" class="btn btn-outline-warning btn-block mb-2" 
                            data-toggle="modal" data-target="#changePasswordModal">
                        <i class="fas fa-key"></i> Ubah Password
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Statistik Singkat --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i> Statistik
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <span class="description-percentage text-success">
                                <i class="fas fa-users"></i>
                            </span>
                            <h5 class="description-header">{{ \App\Models\User::count() }}</h5>
                            <span class="description-text">Total Users</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <span class="description-percentage text-info">
                                <i class="fas fa-calendar"></i>
                            </span>
                            <h5 class="description-header">{{ \App\Models\Booking::count() }}</h5>
                            <span class="description-text">Total Booking</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Change Password --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">
                        <i class="fas fa-key"></i> Ubah Password
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" 
                               name="current_password" 
                               id="current_password"
                               class="form-control @error('current_password') is-invalid @enderror" 
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="form-control @error('password') is-invalid @enderror" 
                               minlength="8"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 8 karakter</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               class="form-control" 
                               minlength="8"
                               required>
                        <small class="text-muted">Ulangi password baru</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.description-block {
    text-align: center;
    padding: 15px 0;
}

.description-header {
    margin: 0;
    padding: 0;
    font-weight: 600;
    font-size: 16px;
}

.description-text {
    text-transform: uppercase;
    font-size: 11px;
}

.description-percentage {
    font-size: 20px;
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Show modal jika ada error password
    @if($errors->has('current_password') || $errors->has('password'))
        $('#changePasswordModal').modal('show');
    @endif
});
</script>
@stop