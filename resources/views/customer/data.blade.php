
@extends('layouts.app')

@section('title', 'Profil User')

@section('content')
    <div class="profile-section">
        <div class="profile-container">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-info">
                    <h1>{{ $user->name }}</h1>
                    <p class="user-role">{{ ucfirst($user->role) }}</p>
                    <p class="join-date">
                        <i class="fas fa-calendar-alt"></i>
                        Bergabung {{ $user->created_at->format('F Y') }}
                    </p>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="profile-content">
                <!-- Menampilkan pesan sukses atau error jika ada -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(!isset($edit) || !$edit)
                    <!-- View Mode - Menampilkan data profil -->
                    <div class="profile-details">
                        <h2>
                            <i class="fas fa-user-circle"></i>
                            Informasi Profil
                        </h2>
                        
                        <div class="details-list">
                            <div class="detail-row">
                                <div class="detail-label">Nama</div>
                                <div class="detail-separator">:</div>
                                <div class="detail-value">{{ $user->name }}</div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Email</div>
                                <div class="detail-separator">:</div>
                                <div class="detail-value">{{ $user->email }}</div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Nomor Telepon</div>
                                <div class="detail-separator">:</div>
                                <div class="detail-value">{{ $user->kontak ?? 'Belum diisi' }}</div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Alamat</div>
                                <div class="detail-separator">:</div>
                                <div class="detail-value">{{ $user->alamat ?? 'Belum diisi' }}</div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Role</div>
                                <div class="detail-separator">:</div>
                                <div class="detail-value">
                                    <span class="role-badge role-{{ $user->role }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Bergabung</div>
                                <div class="detail-separator">:</div>
                                <div class="detail-value">{{ $user->created_at->format('d F Y') }}</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="profile-actions">
                            <a href="{{ route('profile.edit') }}" class="btn-primary">
                                <i class="fas fa-edit"></i>
                                Edit Profil
                            </a>
                            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Edit Mode - Form untuk mengedit profil -->
                    <div class="edit-profile">
                        <h2>
                            <i class="fas fa-user-edit"></i>
                            Edit Profil
                        </h2>
                        
                        <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                            @csrf
                            @method('PUT')

                            <div class="form-list">
                                <!-- Input untuk Nama -->
                                <div class="form-row">
                                    <label for="name" class="form-label">Nama</label>
                                    <div class="form-separator">:</div>
                                    <div class="form-input">
                                        <input 
                                            type="text" 
                                            id="name" 
                                            name="name" 
                                            value="{{ old('name', $user->name) }}" 
                                            required
                                            class="@error('name') is-invalid @enderror"
                                            placeholder="Masukkan nama lengkap"
                                        >
                                        @error('name')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Input untuk Email (readonly) -->
                                <div class="form-row">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="form-separator">:</div>
                                    <div class="form-input">
                                        <input 
                                            type="email" 
                                            id="email" 
                                            name="email" 
                                            value="{{ $user->email }}" 
                                            readonly
                                            class="readonly-input"
                                            title="Email tidak dapat diubah"
                                        >
                                        <small class="form-note">Email tidak dapat diubah</small>
                                    </div>
                                </div>

                                <!-- Input untuk Kontak -->
                                <div class="form-row">
                                    <label for="kontak" class="form-label">Nomor Telepon</label>
                                    <div class="form-separator">:</div>
                                    <div class="form-input">
                                        <input 
                                            type="tel" 
                                            id="kontak" 
                                            name="kontak" 
                                            value="{{ old('kontak', $user->kontak) }}" 
                                            required
                                            class="@error('kontak') is-invalid @enderror"
                                            placeholder="Contoh: 08123456789"
                                        >
                                        @error('kontak')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Input untuk Alamat -->
                                <div class="form-row">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <div class="form-separator">:</div>
                                    <div class="form-input">
                                        <textarea 
                                            id="alamat" 
                                            name="alamat" 
                                            required
                                            class="@error('alamat') is-invalid @enderror"
                                            placeholder="Masukkan alamat lengkap"
                                            rows="4"
                                        >{{ old('alamat', $user->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <button type="submit" class="btn-primary" id="submitBtn">
                                    <i class="fas fa-save"></i>
                                    Simpan Perubahan
                                </button>
                                <a href="{{ route('profile.show') }}" class="btn-secondary">
                                    <i class="fas fa-times"></i>
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

   
@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        const inputs = form.querySelectorAll('input[required], textarea[required]');
        
        // Form validation
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (isValid) {
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            } else {
                e.preventDefault();
            }
        });
        
        // Real-time validation
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    }
});
</script>
@endsection