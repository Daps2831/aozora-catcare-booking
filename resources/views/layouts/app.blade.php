
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aozora Cat Care')</title>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

   
    @vite([
        'resources/css/app.css', 
        'resources/js/app.js',
    ])

    @yield('styles')
    @yield('css')
    
  
    {{-- NAVBAR FIXED CSS + FOOTER CSS --}}
    <style>
        .navbar-wrapper {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1000 !important;
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
        }
        
        .navbar-wrapper nav {
            position: relative !important;
            z-index: 1000 !important;
            background: white !important;
            margin: 0 !important;
        }
        
        /* Body padding responsif */
        body {
            padding-top: 90px !important;
            margin: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            min-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
        }
        
        /* Main content flex grow */
        main {
            flex: 1 !important;
            width: 100% !important;
            margin: 0  !important; 
            padding: 0 !important;
            max-width: none !important; /* TAMBAHKAN untuk override app.css */
        }
        
        /* FOOTER STYLES */
        .main-footer {
            width: 100% !important;
            background: linear-gradient(135deg, #000000ff 0%, #000000ff 100%) !important;
            color: white !important;
            padding: 30px 0 !important;
            margin: 0 !important;
            margin-top: auto !important;
            box-sizing: border-box !important;
            position: relative !important;
            z-index: 1 !important;
        }
        
        .footer-content {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 0 20px !important;
            text-align: center !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .footer-text {
            margin: 0 !important;
            font-size: 16px !important;
            font-weight: 500 !important;
            color: white !important;
            line-height: 1.5 !important;
        }
        
        /* Footer responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 60px !important;
            }
            
            .menu-btn {
                display: flex !important;
            }
            
            .main-footer {
                padding: 25px 0 !important;
            }
            
            .footer-content {
                padding: 0 15px !important;
            }
            
            .footer-text {
                font-size: 14px !important;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding-top: 50px !important;
            }
            
            .main-footer {
                padding: 20px 0 !important;
            }
            
            .footer-content {
                padding: 0 10px !important;
            }
            
            .footer-text {
                font-size: 13px !important;
                line-height: 1.4 !important;
            }
        }
        
        /* Pastikan tidak ada overflow horizontal */
        html, body {
            overflow-x: hidden !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        /* Container untuk memastikan full width */
        * {
            box-sizing: border-box !important;
        }
        
        /* Side menu tetap di atas */
        .side-menu-overlay {
            z-index: 2000 !important;
        }
        
        .side-menu {
            z-index: 2001 !important;
        }

      
    </style>

</head>
<body>
    {{-- NAVBAR - Fixed Position --}}
    <div class="navbar-wrapper">
        @include('partials.navbar')
    </div>

   
    {{-- SIDE MENU - Show for both auth and guest --}}
    <div class="menu-btn" id="menu-btn">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>

    {{-- Overlay --}}
    <div class="side-menu-overlay" id="side-menu-overlay"></div>

    <div class="side-menu" id="side-menu">
        <div class="side-menu-header">
            <button class="close-btn" id="close-btn">
                <i class="fas fa-times"></i>
            </button>
            <div class="user-info">
                @auth
                    <h3>{{ Auth::user()->name }}</h3>
                    <p>{{ Auth::user()->email }}</p>
                @else
                    <h3>Menu Navigasi</h3>
                    <p>Aozora Cat Care</p>
                @endauth
            </div>
        </div>

        <div class="side-menu-content">
            {{-- Menu Navigasi Utama (Selalu tampil untuk semua user) --}}
            <div class="menu-section">
                <h4 class="section-title">Navigasi</h4>
                <a href="{{ url('/') }}" class="profile-option">
                    <i class="fas fa-home"></i>
                    Beranda
                </a>
                <a href="{{ url('/service') }}" class="profile-option">
                    <i class="fas fa-cut"></i>
                    Layanan Kami
                </a>
                <a href="{{ url('/contact') }}" class="profile-option">
                    <i class="fas fa-phone"></i>
                    Kontak Kami
                </a>
            </div>

            @auth
                {{-- Menu Dashboard untuk user yang sudah login --}}
                <div class="menu-section">
                    <h4 class="section-title">Dashboard</h4>
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="profile-option">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </div>

                {{-- Menu Profil untuk user yang sudah login --}}
                <div class="menu-section">
                    <h4 class="section-title">Profil & Akun</h4>
                    <a href="{{ route('profile.show') }}" class="profile-option">
                        <i class="fas fa-user"></i>
                        Profil Saya
                    </a>
                </div>

                {{-- Menu Layanan Booking untuk user biasa --}}
                @if(Auth::user()->role !== 'admin')
                    <div class="menu-section">
                        <h4 class="section-title">Layanan Booking</h4>
                        <a href="{{ url('/booking') }}" class="profile-option">
                            <i class="fas fa-calendar-plus"></i>
                            Booking Jadwal
                        </a>
                        <a href="{{ route('customer.riwayat') }}" class="profile-option">
                            <i class="fas fa-history"></i>
                            Riwayat Booking
                        </a>
                        <a href="{{ route('kucing.register') }}" class="profile-option">
                            <i class="fas fa-cat"></i>
                            Daftarkan Kucing
                        </a>
                    </div>
                @endif


                {{-- Logout Section --}}
                <div class="menu-section">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-button">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            @else
                {{-- Menu untuk guest (belum login) --}}
                <div class="menu-section">
                    <h4 class="section-title">Akun</h4>
                    <a href="{{ route('register.form') }}" class="profile-option">
                        <i class="fas fa-user-plus"></i>
                        Daftar
                    </a>
                    <a href="{{ route('login.form') }}" class="profile-option">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk
                    </a>
                </div>
            @endauth
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')
    
    @yield('js')
    @yield('scripts')
</body>
</html>