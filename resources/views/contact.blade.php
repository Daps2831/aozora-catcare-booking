
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
            <p>Telepon: <a href="tel:+6283831833743">+62 838-3183-3743</a></p>
        </div>
        
        <div class="contact-item">
            <img src="{{ asset('images/location-icon.png') }}" alt="Location Icon">
            <p>Nantikan lokasi kantornya.</p>
        </div>
    </div>
</section>
@endsection

@section('css')
<style>
.contact-section {
    max-width: 800px;
    margin: 2rem auto;
    padding: 3rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.contact-info {
    text-align: center;
}

.contact-info h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 3rem;
    position: relative;
}

.contact-info h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 2px;
}

.contact-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
    border-left: 4px solid #667eea;
}

.contact-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    background: #ffffff;
}

.contact-item img {
    width: 32px;
    height: 32px;
    object-fit: contain;
    flex-shrink: 0;
}

.contact-item p {
    margin: 0;
    font-size: 1.1rem;
    color: #4a5568;
    font-weight: 500;
}

.contact-item a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.contact-item a:hover {
    color: #764ba2;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .contact-section {
        margin: 1rem;
        padding: 2rem 1rem;
    }
    
    .contact-info h2 {
        font-size: 2rem;
        margin-bottom: 2rem;
    }
    
    .contact-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
        padding: 1.2rem;
    }
    
    .contact-item p {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .contact-item {
        margin-bottom: 1.5rem;
        padding: 1rem;
    }
    
    .contact-info h2 {
        font-size: 1.8rem;
    }
}
</style>
@endsection