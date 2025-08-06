
@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <section class="login-section">
        <div class="login-container">
            <h1>Masuk ke Akun Anda</h1>

            <!-- Menampilkan pesan sukses atau error jika ada -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
                @csrf
                
                <!-- Input Email -->
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
                        placeholder="Masukkan email Anda"
                    >
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        autocomplete="current-password"
                        class="@error('password') is-invalid @enderror"
                        placeholder="Masukkan password Anda"
                    >
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-group">
                    <label class="remember-checkbox">
                        <input type="checkbox" name="remember" id="remember" 
                            value="1" 
                            {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>
                </div>

                <!-- Tombol Masuk -->
                <div class="form-group">
                    <button type="submit" class="cta-btn-diteks" id="submitBtn">
                        Masuk
                    </button>
                </div>
            </form>

            <div class="form-footer">
                <p>Belum punya akun? <a href="{{ route('register.form') }}">Daftar sekarang</a></p>
                
                @if(Route::has('password.request'))
                    <p style="margin-top: 10px;">
                        <a href="{{ route('password.request') }}">Lupa password?</a>
                    </p>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const inputs = form.querySelectorAll('input[required]');
    const rememberCheckbox = document.getElementById('remember');
    
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
            submitBtn.textContent = 'Memproses...';
            
            // Log remember me status (for debugging)
            console.log('Remember Me:', rememberCheckbox.checked);
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
    
    // Remember me persistence (optional)
    if (localStorage.getItem('remember_email')) {
        document.getElementById('email').value = localStorage.getItem('remember_email');
        rememberCheckbox.checked = true;
    }
    
    // Save email if remember me is checked
    form.addEventListener('submit', function() {
        if (rememberCheckbox.checked) {
            localStorage.setItem('remember_email', document.getElementById('email').value);
        } else {
            localStorage.removeItem('remember_email');
        }
    });
});
</script>
@endsection