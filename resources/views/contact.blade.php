@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')
    <section class="contact-section">
        <div class="contact-info">
            <h2>Kontak Kami</h2>
            <div class="contact-item">
                <img src="{{ asset('images/email-icon.png') }}" alt="Email Icon">
                <p>Email: <a href="mailto:hello@aozoracatcare.com">hello@aozoracatcare.com</a></p>
            </div>
            <div class="contact-item">
                <img src="{{ asset('images/phone-icon.png') }}" alt="Phone Icon">
                <p>Telepon: <a href="tel:+15550000000">+1 (555) 000-0000</a></p>
            </div>
            <div class="contact-item">
                <img src="{{ asset('images/location-icon.png') }}" alt="Location Icon">
                <p>Kantor: 123 Sample St, Sydney NSW 2000 AU .</p>
                <a href="#" class="get-directions">Dapatkan Petunjuk</a>
            </div>
        </div>
    </section>
@endsection
