
{{-- filepath: c:\laragon\www\testing\resources\views\admin\users\create.blade.php --}}
@extends('adminlte::page')
@section('title', 'Tambah User')
@section('content_header')
    <h1>Tambah User Baru</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-plus"></i> Form Tambah User
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

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            {{-- Informasi Akun User --}}
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Akun</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" 
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
                                       value="{{ old('username') }}" 
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Username harus unik</small>
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
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Role <span class="text-danger">*</span></label>
                                <select name="role" 
                                        id="role"
                                        class="form-control @error('role') is-invalid @enderror" 
                                        required>
                                    <option value="">Pilih Role</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User(Customer)</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Role Admin tidak dapat dibuat melalui form ini</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
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
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation"
                                    class="form-control" 
                                    minlength="8"
                                    required>
                                <small class="text-muted">Ulangi password yang sama (minimal 8 karakter)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Customer (Optional) --}}
            <div class="card mb-3" id="customer-info" style="display: none;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-address-card"></i> Informasi Customer (Optional)</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Info:</strong> Data customer dapat diisi sekarang atau diisi kemudian saat user sudah terdaftar.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_name">Nama Customer</label>
                                <input type="text" 
                                       name="customer_name" 
                                       id="customer_name"
                                       class="form-control @error('customer_name') is-invalid @enderror" 
                                       value="{{ old('customer_name') }}" 
                                       placeholder="Nama lengkap customer">
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_username">Username Customer</label>
                                <input type="text" 
                                       name="customer_username" 
                                       id="customer_username"
                                       class="form-control @error('customer_username') is-invalid @enderror" 
                                       value="{{ old('customer_username') }}" 
                                       placeholder="Username customer">
                                @error('customer_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_email">Email Customer</label>
                                <input type="email" 
                                       name="customer_email" 
                                       id="customer_email"
                                       class="form-control @error('customer_email') is-invalid @enderror" 
                                       value="{{ old('customer_email') }}" 
                                       placeholder="Email customer (bisa sama dengan email akun)">
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_kontak">Nomor Telepon</label>
                                <input type="text" 
                                       name="customer_kontak" 
                                       id="customer_kontak"
                                       class="form-control @error('customer_kontak') is-invalid @enderror" 
                                       value="{{ old('customer_kontak') }}" 
                                       placeholder="Nomor telepon customer">
                                @error('customer_kontak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_alamat">Alamat</label>
                        <textarea name="customer_alamat" 
                                  id="customer_alamat"
                                  class="form-control @error('customer_alamat') is-invalid @enderror" 
                                  rows="3" 
                                  placeholder="Alamat lengkap customer">{{ old('customer_alamat') }}</textarea>
                        @error('customer_alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan User
                </button>
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
.text-danger {
    color: #dc3545 !important;
}

.card-header.bg-primary {
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.card-header.bg-info {
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

#customer-info {
    transition: all 0.3s ease;
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Show/Hide customer info berdasarkan role
    $('#role').change(function() {
        const role = $(this).val();
        if (role === 'customer' || role === 'user') {
            $('#customer-info').slideDown();
        } else {
            $('#customer-info').slideUp();
        }
    });
    
    // Trigger change event jika ada old value
    $('#role').trigger('change');
    
    // Auto-fill customer email dari email akun
    $('#email').on('blur', function() {
        const email = $(this).val();
        if (email && !$('#customer_email').val()) {
            $('#customer_email').val(email);
        }
    });
    
    // Auto-fill customer name dari nama akun
    $('#name').on('blur', function() {
        const name = $(this).val();
        if (name && !$('#customer_name').val()) {
            $('#customer_name').val(name);
        }
    });
    
    // Auto-generate username dari nama (optional)
    $('#name').on('blur', function() {
        const name = $(this).val();
        if (name && !$('#username').val()) {
            // Generate username dari nama (lowercase, hapus spasi)
            const username = name.toLowerCase().replace(/\s+/g, '');
            $('#username').val(username);
        }
    });
});
</script>
@stop