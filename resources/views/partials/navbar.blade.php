{{-- file: resources/views/partials/navbar.blade.php --}}
<nav>
    <div class="logo-container">
        <img src="{{ asset('images/logo.png') }}" alt="Aozora Cat Care Logo" />
    </div>
    <ul>
        @auth
            {{-- Tampilkan link Dashboard HANYA jika sudah login --}}
            <li>
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}">
                    Dashboard
                </a>
            </li>
        @endauth

        <li><a href="{{ url('/') }}">Beranda</a></li>
        <li><a href="{{ url('service') }}">Layanan Kami</a></li>
        <li><a href="{{ url('contact') }}">Kontak Kami</a></li>

        @guest
            {{-- Tombol Daftar dan Masuk HANYA untuk tamu (belum login) --}}
            <li><a href="{{ route('register.form') }}" class="cta-btn-putih">Daftar</a></li>
            <li><a href="{{ route('login.form') }}" class="cta-btn-masuk">Masuk</a></li>
        @endguest
    </ul>
</nav>