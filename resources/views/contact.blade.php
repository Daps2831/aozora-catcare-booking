@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')
    <section class="contact-section">
        <div class="contact-info">
            <h2>Kontak Kami</h2>
            <div class="contact-item">
                <img src="{{ asset('images/email-icon.png') }}" alt="Email Icon">
                <p>Email: <a href="mailto:aozoracare6@gmail.com">aozoracare6@gmail.com</a></p>
            </div>
            <div class="contact-item">
                <img src="{{ asset('images/phone-icon.png') }}" alt="Phone Icon">
                <p>Telepon: <a href="tel:+6283831833743">+6283831833743</a></p>
            </div>
            <div class="contact-item">
                <img src="{{ asset('images/location-icon.png') }}" alt="Location Icon">
                <p>Nantikan lokasi kantornya .</p>
                <a href="#" class="get-directions"></a>
            </div>
        </div>
    </section>
@endsection
