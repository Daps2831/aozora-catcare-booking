<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aozora Cat Care')</title>
    <!-- Include the CSS file -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
 
    <!-- Include the navigation bar -->
    @include('partials.navbar')

    <!-- Main content section -->
    <main>
        @yield('content') <!-- Page-specific content will go here -->
    </main>

    <!-- Include the footer -->
    @include('partials.footer')

    <!-- Include the JavaScript file -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
