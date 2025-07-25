<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aozora Cat Care')</title>

 
    @vite([
        'resources/css/app.css', 
        'resources/js/app.js',
        //'public/build/assets/app-Bf0HZVHS.js', 
        //'public/build/assets/app-DUKMSq9A.css',
        //'public/build/assets/app-z-s-FgWg.css',

    ])

    @yield('styles')
</head>
<body>
 
    @include('partials.navbar')

    {{-- TEMPEL KODE MENU DI SINI DAN BUNGKUS DENGAN @auth --}}
    @auth
        <div class="menu-btn" id="menu-btn">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>

        <div class="side-menu" id="side-menu">
            <button class="close-btn" id="close-btn">X</button>
            <div class="side-menu-content">
                <a href="{{ route('profile.show') }}" class="profile-option">Profil Saya</a>
                <a href="{{ url('/booking') }}" class="profile-option">Booking Jadwal</a>
                <a href="{{ route('customer.riwayat') }}" class="profile-option">Riwayat Booking</a>
                <form method="POST" action="{{ route('logout') }}" class="profile-option">
                    @csrf
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            </div>
        </div>
    @endauth
    {{-- AKHIR BAGIAN MENU --}}

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @yield('scripts')
</body>
</html>