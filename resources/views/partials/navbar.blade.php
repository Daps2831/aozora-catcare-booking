
{{-- file: resources/views/partials/navbar.blade.php --}}
<nav>
    <div class="logo-container">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Aozora Cat Care Logo" />
        </a>
    </div>
    
    {{-- Desktop Menu --}}
    <ul class="nav-menu">
        <li><a href="{{ url('/') }}">Beranda</a></li>
        <li><a href="{{ url('/service') }}">Layanan Kami</a></li>
        <li><a href="{{ url('/contact') }}">Kontak Kami</a></li>

        @auth
            {{-- Link Dashboard berdasarkan role user --}}
            <li>
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}">
                    Dashboard
                </a>
            </li>
            
            {{-- User Dropdown Menu dengan alignment yang sejajar --}}
            <li class="user-dropdown">
                <a href="#" class="user-dropdown-toggle" id="userDropdown">
                    <i class="fas fa-user"></i>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="{{ route('profile.show') }}" class="dropdown-item">
                        <i class="fas fa-user-circle"></i>
                        Profil Saya
                    </a>
                    
                    @if(Auth::user()->role !== 'admin')
                        <a href="{{ route('customer.riwayat') }}" class="dropdown-item">
                            <i class="fas fa-history"></i>
                            Riwayat Booking
                        </a>
                        <a href="{{ route('kucing.register') }}" class="dropdown-item">
                            <i class="fas fa-cat"></i>
                            Daftarkan Kucing
                        </a>
                    @endif
                    
                    <div class="dropdown-divider"></div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="dropdown-form">
                        @csrf
                        <button type="submit" class="dropdown-item logout-item">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        @else
            {{-- Tombol Daftar dan Masuk untuk guest --}}
            <li><a href="{{ route('register.form') }}" class="cta-btn-putih">Daftar</a></li>
            <li><a href="{{ route('login.form') }}" class="cta-btn-masuk">Masuk</a></li>
        @endauth
    </ul>
</nav>