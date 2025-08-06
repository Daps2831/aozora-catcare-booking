
@extends('layouts.app')

@section('title', 'Layanan Kami')

@section('content')
    <section class="service-gallery">
        <h1>Layanan Kami</h1>
        <p>Berikut adalah layanan grooming yang kami tawarkan:</p>
        <div class="gallery">
            <div class="gallery-item">
                <img src="{{ asset('images/exclusive.jpg') }}" alt="Layanan Ekslusif">
                <p>Layanan Ekslusif</p>
            </div>
            <div class="gallery-item">
                <img src="{{ asset('images/grooming1.jpg') }}" alt="Layanan Grooming 1">
                <p>Layanan Grooming 1</p>
            </div>
            <div class="gallery-item">
                <img src="{{ asset('images/grooming2.jpg') }}" alt="Layanan Grooming 2">
                <p>Layanan Grooming 2</p>
            </div>
            <div class="gallery-item">
                <img src="{{ asset('images/addition.jpg') }}" alt="Layanan Tambahan">
                <p>Layanan Tambahan</p>
            </div>
        </div>
    </section>
@endsection

<style>
.service-gallery .gallery {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* Menyusun gambar dalam satu baris */
    gap: 20px;
    margin-top: 20px;
}

.service-gallery .gallery-item {
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    background-color: #f9f9f9;
}

.service-gallery .gallery-item img {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
}
</style>