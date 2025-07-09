@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
    <!-- Tombol garis tiga untuk membuka menu samping -->
    <div id="menu-btn" class="menu-btn">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>

    <!-- Menu Samping -->
    <div id="side-menu" class="side-menu">
        <!-- Tombol untuk menutup menu samping -->
        <button id="close-btn" class="close-btn">X</button>

        <div class="side-menu-content">
             <a href="{{ route('profile.show') }}" class="profile-option">Profil</a>
            <a href="#">Layanan</a>
            <a href="#">Jadwal Grooming</a>
            <a href="#">Chatbot</a>
        </div>
    </div>

    <h2>Welcome User, {{ $user->name }}!</h2>
    <!-- <p>Email: {{ $user->email }}</p>
    <p>Kontak: {{ $user->kontak }}</p>
    <p>Alamat: {{ $user->alamat }}</p>-->
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/script.js') }}"></script>
@endsection
