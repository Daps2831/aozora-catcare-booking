
@extends('adminlte::page')
@section('title', 'Edit Profil')
@section('content_header')
    <h1>Edit Profil</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit"></i> Form Edit Profil
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

        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
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
                            </div>
                        </div>
                    </div>
                    
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
                    
                    <div class="form-group">
                        <label>Role Saat Ini</label>
                        <div class="mt-2">
                            <span class="badge badge-danger badge-lg">
                                {{ ucfirst($user->role) }}
                            </span>
                            <small class="text-muted ml-2">Role tidak dapat diubah</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle"></i> Informasi
                            </h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Terdaftar:</strong><br>{{ $user->created_at->format('d F Y') }}</p>
                            <p><strong>Terakhir Update:</strong><br>{{ $user->updated_at->format('d F Y, H:i') }}</p>
                            <hr>
                            <p class="text-muted">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Pastikan email yang Anda masukkan masih aktif untuk keperluan notifikasi sistem.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
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

.text-danger {
    color: #dc3545 !important;
}
</style>
@stop