@extends('layouts.app')

@section('title', 'Registrasi Akun')

@section('content')
    <section class="register-section">
        <div class="register-container">
            <h1>Registrasi Akun</h1>
            <p>Silakan isi formulir di bawah ini untuk membuat akun dan bergabung dengan layanan grooming kucing kami.</p>

            <!-- Menampilkan pesan kesalahan jika ada -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terdapat kesalahan:</strong>
                    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Registrasi -->
            <form action="{{ route('register.submit') }}" method="POST" id="registerForm">
                @csrf  <!-- Token CSRF untuk keamanan -->

                <!-- Name -->
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required
                        autocomplete="name"
                        class="@error('name') is-invalid @enderror"
                        placeholder="Masukkan nama lengkap Anda"
                    >
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}" 
                        required
                        autocomplete="username"
                        class="@error('username') is-invalid @enderror"
                        placeholder="Masukkan username unik"
                    >
                    @error('username')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required
                        autocomplete="email"
                        class="@error('email') is-invalid @enderror"
                        placeholder="Masukkan alamat email Anda"
                    >
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kontak -->
                <div class="form-group">
                    <label for="kontak">Nomor Telepon</label>
                    <input 
                        type="tel" 
                        id="kontak" 
                        name="kontak" 
                        value="{{ old('kontak') }}" 
                        required
                        autocomplete="tel"
                        class="@error('kontak') is-invalid @enderror"
                        placeholder="Contoh: 08123456789"
                    >
                    @error('kontak')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea 
                        id="alamat" 
                        name="alamat" 
                        required
                        class="@error('alamat') is-invalid @enderror"
                        placeholder="Masukkan alamat lengkap Anda"
                        rows="3"
                    >{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        autocomplete="new-password"
                        class="@error('password') is-invalid @enderror"
                        placeholder="Minimal 8 karakter"
                    >
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password yang sama"
                    >
                </div>

                <div class="form-group">
                    <button type="submit" class="cta-btn-diteks" id="submitBtn">
                        Daftar Sekarang
                    </button>
                </div>
            </form>

            <div class="form-footer">
                <p>Sudah memiliki akun? <a href="{{ route('login.form') }}">Masuk di sini</a></p>
            </div>
        </div>
    </section>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        // Check password confirmation
        if (passwordInput.value !== confirmPasswordInput.value) {
            isValid = false;
            confirmPasswordInput.classList.add('is-invalid');
            // Show error message
            let existingError = confirmPasswordInput.parentNode.querySelector('.error');
            if (!existingError) {
                let errorDiv = document.createElement('div');
                errorDiv.className = 'error';
                errorDiv.textContent = 'Password konfirmasi tidak sama';
                confirmPasswordInput.parentNode.appendChild(errorDiv);
            }
        } else {
            confirmPasswordInput.classList.remove('is-invalid');
            let existingError = confirmPasswordInput.parentNode.querySelector('.error');
            if (existingError) existingError.remove();
        }
        
        if (isValid) {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';
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
    
    // Password confirmation real-time check
    confirmPasswordInput.addEventListener('input', function() {
        if (this.value === passwordInput.value) {
            this.classList.remove('is-invalid');
            let existingError = this.parentNode.querySelector('.error');
            if (existingError) existingError.remove();
        }
    });
});
</script>
@endsection