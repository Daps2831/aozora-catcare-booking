
{{-- filepath: c:\laragon\www\testing\resources\views\admin\users\edit.blade.php --}}
@extends('adminlte::page')
@section('title', 'Edit User')
@section('content_header')
    <h1>Edit User: {{ $user->name }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit"></i> Form Edit User
        </h3>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
       
            {{-- Update bagian informasi akun user --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" 
                            name="name" 
                            id="name"
                            class="form-control @error('name') is-invalid @enderror" 
                            value="{{ old('name', $user->name) }}" 
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" 
                            name="username" 
                            id="username"
                            class="form-control @error('username') is-invalid @enderror" 
                            value="{{ old('username', $user->username) }}" 
                            required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Username customer akan otomatis berubah mengikuti username ini</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" 
                            name="email" 
                            id="email"
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email', $user->email) }}" 
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" 
                            name="password" 
                            id="password"
                            class="form-control @error('password') is-invalid @enderror" 
                            minlength="8"
                            placeholder="Kosongkan jika tidak ingin mengubah password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 8 karakter</small>
                    </div>
                </div>
            </div>

            {{-- Informasi Customer (untuk semua user kecuali admin) --}}
            @if($user->role !== 'admin')
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-address-card"></i> Informasi Customer</h5>
                </div>
                <div class="card-body">
                    {{-- Info tentang username sync --}}
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Username Customer:</strong> 
                        <span class="badge badge-primary">{{ $user->customer->username ?? $user->username }}</span>
                        <small class="text-muted">(Otomatis sama dengan username akun)</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_name">Nama Customer</label>
                                <input type="text" 
                                    name="customer_name" 
                                    id="customer_name"
                                    class="form-control @error('customer_name') is-invalid @enderror" 
                                    value="{{ old('customer_name', $user->customer->name ?? '') }}" 
                                    placeholder="Nama lengkap customer">
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_email">Email Customer</label>
                                <input type="email" 
                                    name="customer_email" 
                                    id="customer_email"
                                    class="form-control @error('customer_email') is-invalid @enderror" 
                                    value="{{ old('customer_email', $user->customer->email ?? '') }}" 
                                    placeholder="Email customer">
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_kontak">Nomor Telepon</label>
                                <input type="text" 
                                    name="customer_kontak" 
                                    id="customer_kontak"
                                    class="form-control @error('customer_kontak') is-invalid @enderror" 
                                    value="{{ old('customer_kontak', $user->customer->kontak ?? '') }}" 
                                    placeholder="Nomor telepon customer">
                                @error('customer_kontak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="customer_alamat">Alamat</label>
                                <textarea name="customer_alamat" 
                                        id="customer_alamat"
                                        class="form-control @error('customer_alamat') is-invalid @enderror" 
                                        rows="3" 
                                        placeholder="Alamat lengkap customer">{{ old('customer_alamat', $user->customer->alamat ?? '') }}</textarea>
                                @error('customer_alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update User
                </button>
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
<style>
.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.75em;
}

.card-header.bg-primary {
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.card-header.bg-info {
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.text-danger {
    color: #dc3545 !important;
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Real-time preview username customer
    $('#username').on('input', function() {
        const username = $(this).val();
        $('.badge-primary').text(username || '{{ $user->username }}');
        
        // Visual feedback
        $('.alert-info').addClass('border-warning');
        setTimeout(function() {
            $('.alert-info').removeClass('border-warning');
        }, 300);
    });
    
    // Konfirmasi jika username berubah
    let originalUsername = '{{ $user->username }}';
    $('#username').on('blur', function() {
        const newUsername = $(this).val();
        if (newUsername !== originalUsername && newUsername !== '') {
            $(this).after('<small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Username customer akan otomatis berubah menjadi "' + newUsername + '"</small>');
            
            // Remove warning after 3 seconds
            setTimeout(function() {
                $('#username').siblings('.text-warning').remove();
            }, 3000);
        }
    });
});
</script>
@stop