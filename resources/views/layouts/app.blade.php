<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aozora Cat Care')</title>

 
    @vite([
        'resources/css/app.css', 
        'resources/js/app.js',
        'resources/css/vanilla-calendar-pro-layout.css',
        'resources/css/vanilla-calendar-pro-light.css',
       
    ])

    @yield('styles')
</head>
<body>
 
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @yield('scripts')
</body>
</html>