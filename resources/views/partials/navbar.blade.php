<nav>
    <div class="logo-container">
        <img src="{{ asset('images/logo.png') }}" alt="Aozora Cat Care Logo" />
    </div>
    <ul>
        <li><a href="{{ url('user/dashboard') }}">Dashboard</a></li>
        <li><a href="{{ url('/') }}">Beranda</a></li>
        <li><a href="{{ url('service') }}">Layanan Kami</a></li>
        <li><a href="{{ url('contact') }}">Kontak Kami</a></li>
        <li><a href="{{ url('register') }}" class="cta-btn-putih">Daftar</a></li>
        <li><a href="{{ url('login') }}" class="cta-btn-masuk">Masuk</a></li>
    </ul>
</nav>
