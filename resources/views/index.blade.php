@extends('layouts.app')

@section('title', 'Layanan Grooming Kucing')

@section('content')
    <section class="intro">
        <div class="text-content">
            <h1>Selamat datang di layanan grooming kucing kami!</h1>
            <p>Website kami memudahkan Anda untuk merawat kucing kesayangan dengan layanan grooming profesional. Bergabunglah sekarang dan nikmati pengalaman terbaik untuk kucing Anda.</p>
            <div class="cta-buttons">
                @guest
                    {{-- Tampilkan tombol ini HANYA jika belum login --}}
                    <a href="{{ route('register.form') }}" class="cta-btn-diteks">Daftar</a>
                @endguest

                @auth
                    {{-- Tampilkan tombol ini HANYA jika sudah login --}}
                    {{-- Arahkan ke halaman booking atau pendaftaran kucing --}}
                    <a href="{{ route('kucing.register') }}" class="cta-btn-diteks">Booking Sekarang</a>
                @endauth

                <a href="{{ url('pageinfo') }}" class="cta-btn-putih diteks">Lainnya</a>
            </div>
        </div>
        <div class="image-content">
            <img src="{{ asset('images/template1.jpg') }}" alt="Grooming Image 1" />
            <img src="{{ asset('images/template2.jpg') }}" alt="Grooming Image 2" />
            <img src="{{ asset('images/template3.jpg') }}" alt="Grooming Image 3" />
            <img src="{{ asset('images/template4.jpg') }}" alt="Grooming Image 4" />
        </div>
    </section>
@endsection
