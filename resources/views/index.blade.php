
@extends('layouts.app')

@section('title', 'Beranda - Aozora Cat Care')

@section('content')
<div class="homepage-wrapper">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-text">
                    <div class="hero-badge">
                        <i class="fas fa-star"></i>
                        <span>Layanan Grooming Terpercaya</span>
                    </div>
                    <h1 class="hero-title">
                        Selamat datang di 
                        <span class="highlight">Aozora Cat Care</span>
                    </h1>
                    <p class="hero-description">
                        Website kami memudahkan Anda untuk merawat kucing kesayangan dengan layanan grooming profesional. Bergabunglah sekarang dan nikmati pengalaman terbaik untuk kucing Anda.
                    </p>
                    
                    <div class="hero-actions">
                        @guest
                            <a href="{{ route('register.form') }}" class="btn-primary">
                                <i class="fas fa-user-plus"></i>
                                Daftar Sekarang
                            </a>
                            <a href="{{ route('login.form') }}" class="btn-secondary">
                                <i class="fas fa-sign-in-alt"></i>
                                Masuk
                            </a>
                        @endguest

                        @auth
                            <a href="{{ route('booking.index') }}" class="btn-primary">
                                <i class="fas fa-calendar-plus"></i>
                                Booking Sekarang
                            </a>
                            <a href="{{ route('kucing.register') }}" class="btn-secondary">
                                <i class="fas fa-cat"></i>
                                Daftarkan Kucing
                            </a>
                        @endauth
                    </div>

                    <!-- Stats Section -->
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Kucing Dirawat</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">4.9</div>
                            <div class="stat-label">Rating Pelanggan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Layanan Konsultasi</div>
                        </div>
                    </div>
                </div>

                <div class="hero-images">
                    <div class="image-grid">
                        <div class="image-item main-image">
                            <img src="{{ asset('images/template1.jpg') }}" alt="Grooming Professional" />
                            <div class="image-overlay">
                                <span>Grooming Professional</span>
                            </div>
                        </div>
                        <div class="image-item">
                            <img src="{{ asset('images/template2.jpg') }}" alt="Kucing Sehat" />
                            <div class="image-overlay">
                                <span>Kucing Sehat</span>
                            </div>
                        </div>
                        <div class="image-item">
                            <img src="{{ asset('images/template3.jpg') }}" alt="Perawatan Berkualitas" />
                            <div class="image-overlay">
                                <span>Perawatan Terbaik</span>
                            </div>
                        </div>
                        <div class="image-item">
                            <img src="{{ asset('images/template4.jpg') }}" alt="Kucing Bahagia" />
                            <div class="image-overlay">
                                <span>Kucing Bahagia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Preview Section -->
    <section class="services-preview">
        <div class="services-container">
            <div class="section-header">
                <h2>Layanan Unggulan Kami</h2>
                <p>Memberikan perawatan eksklusif terbaik untuk kucing kesayangan Anda</p>
            </div>
            
            <div class="services-grid">
                <div class="service-card exclusive">
                    <div class="service-badge">
                        <span>EXCLUSIVE</span>
                    </div>
                    <div class="service-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3>Treatment Kutu</h3>
                    <div class="service-price">IDR 119.000</div>
                    <p>Treatment eksklusif untuk kutu yang cocok dengan jenis kulit kucing anda. Menggunakan obat kutu yang aman dan berkualitas tinggi untuk kulit yang sensitive hingga kulit kucing dengan kondisi normal.</p>
                    
                    <div class="service-features">
                        <div class="feature-row">
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>POTONG KUKU</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>BULU MATA</span>
                            </div>
                        </div>
                        <div class="feature-row">
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>POTONG BULU EKOR</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>PIJAT KUTU</span>
                            </div>
                        </div>
                        <div class="feature-row">
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>MEMBUNUH BAKTERI</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>PEMBERSIHAN KUTU ANJORA</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="service-card exclusive">
                    <div class="service-badge">
                        <span>EXCLUSIVE</span>
                    </div>
                    <div class="service-icon">
                        <i class="fas fa-paw"></i>
                    </div>
                    <h3>Treatment Jamur</h3>
                    <div class="service-price">IDR 119.000</div>
                    <p>Treatment eksklusif khusus jamur yang sengar khusus kucing kering sepat bagi jamur hingga jamur berat dan kemolek usang yang memerlukan ciri-giri khusus bagi kucing sepat tersebut.</p>
                    
                    <div class="service-features">
                        <div class="feature-row">
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>POTONG KUKU</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>SABUN ANTI JAMUR</span>
                            </div>
                        </div>
                        <div class="feature-row">
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>POTONG BULU BULU</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>PEMBERSIH JAMUR JAMUR</span>
                            </div>
                        </div>
                        <div class="feature-row">
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>MEMBUNUH KUMAN</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>FORMULA KHUSUS ANTI JAMUR</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="service-card exclusive">
                    <div class="service-badge">
                        <span>EXCLUSIVE</span>
                    </div>
                    <div class="service-icon">
                        <i class="fas fa-shield-virus"></i>
                    </div>
                    <h3>Treatment Scabies</h3>
                    <div class="service-price">IDR 109.000</div>
                    <p>Treatment eksklusif khusus scabies yang spesifilis scabies untuk kucing. Penanganan scabies kering dan basah yang dirancang khusus pada kulit kucing yang terkinfeksi scabies sebagai penyakit serangga berkinfeksi.</p>
                    
                    <div class="service-features">
                        <div class="feature-row">
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>POTONG KUKU</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>SABUN ANTI SCABIES</span>
                            </div>
                        </div>
                        <div class="feature-row">
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>POTONG BULU BULU</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>PEMBERSIH TUNGAU JAMUR</span>
                            </div>
                        </div>
                        <div class="feature-row">
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>MEMBUNUH VIRUS</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>FORMULA KHUSUS SCABIES AZOORA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="services-cta">
                <a href="{{ url('/service') }}" class="btn-outline">
                    <i class="fas fa-arrow-right"></i>
                    Lihat Semua Layanan
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us">
        <div class="why-container">
            <div class="why-content">
                <div class="why-text">
                    <h2>Mengapa Memilih Aozora Cat Care?</h2>
                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="feature-text">
                                <h4>Tenaga Ahli Berpengalaman</h4>
                                <p>Tim groomer profesional dengan pengalaman lebih dari 1 tahun</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="feature-text">
                                <h4>Layanan Home Service</h4>
                                <p>Kami datang ke rumah Anda untuk kenyamanan kucing kesayangan</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="feature-text">
                                <h4>Booking Mudah & Fleksibel</h4>
                                <p>Sistem booking online 24/7 dengan jadwal yang fleksibel</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="feature-text">
                                <h4>Penanganan dengan Cinta</h4>
                                <p>Setiap kucing diperlakukan dengan penuh kasih sayang dan kesabaran</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="why-image">
                    <div class="image-wrapper">
                        <img src="{{ asset('images/template1.jpg') }}" alt="Professional Cat Care" />
                        <div class="floating-card">
                            <div class="card-content">
                                <i class="fas fa-certificate"></i>
                                <div>
                                    <strong>Bersertifikat</strong>
                                    <span>Groomer Professional</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-container">
            <div class="cta-content">
                <h2>Siap Memberikan Perawatan Terbaik untuk Kucing Anda?</h2>
                <p>Bergabunglah dengan ratusan pemilik kucing yang mempercayakan perawatan kepada kami</p>
                
                <div class="cta-actions">
                    @guest
                        <a href="{{ route('register.form') }}" class="btn-primary large">
                            <i class="fas fa-rocket"></i>
                            Mulai Sekarang
                        </a>
                        <a href="{{ url('/contact') }}" class="btn-outline large">
                            <i class="fas fa-phone"></i>
                            Hubungi Kami
                        </a>
                    @endguest

                    @auth
                        <a href="{{ route('booking.index') }}" class="btn-primary large">
                            <i class="fas fa-calendar-check"></i>
                            Buat Booking
                        </a>
                        <a href="{{ route('user.dashboard') }}" class="btn-outline large">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('styles')
<style>
/* =============================================== */
/* HOMEPAGE STYLES */
/* =============================================== */

.homepage-wrapper {
    width: 100%;
    overflow-x: hidden;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 80px 0;
    min-height: 80vh;
    display: flex;
    align-items: center;
}

.hero-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
}

.hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.hero-text {
    animation: fadeInUp 1s ease-out;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
}

.hero-badge i {
    color: #ffd700;
}

.hero-title {
    font-size: 48px;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 20px;
    color: #333;
}

.hero-title .highlight {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-description {
    font-size: 18px;
    line-height: 1.6;
    color: #666;
    margin-bottom: 30px;
}

.hero-actions {
    display: flex;
    gap: 15px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.btn-primary,
.btn-secondary,
.btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 15px 25px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    cursor: pointer;
    white-space: nowrap;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: white;
    color: #333;
    border-color: #e9ecef;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.btn-secondary:hover {
    background: #f8f9fa;
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
    text-decoration: none;
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border-color: #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

.btn-primary.large,
.btn-outline.large {
    padding: 18px 30px;
    font-size: 18px;
}

/* Hero Stats */
.hero-stats {
    display: flex;
    gap: 30px;
    margin-top: 40px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #667eea;
    line-height: 1;
}

.stat-label {
    font-size: 14px;
    color: #666;
    margin-top: 5px;
}

/* Hero Images */
.hero-images {
    animation: fadeInRight 1s ease-out;
}

.image-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    height: 500px;
}

.image-item {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.image-item:hover {
    transform: translateY(-5px);
}

.image-item.main-image {
    grid-row: span 2;
}

.image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    padding: 20px 15px 15px;
    font-weight: 600;
}

/* Services Preview Section */
.services-preview {
    padding: 80px 0;
    background: white;
}

.services-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    text-align: center;
}

.section-header {
    margin-bottom: 50px;
}

.section-header h2 {
    font-size: 36px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.section-header p {
    font-size: 18px;
    color: #666;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

/* Enhanced Service Cards */
.service-card {
    background: white;
    padding: 30px 25px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
}

.service-card.exclusive {
    border: 2px solid #667eea;
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

.service-card:hover {
    transform: translateY(-5px);
    border-color: #667eea;
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.15);
}

.service-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 20px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    border-bottom-left-radius: 15px;
}

.service-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 30px;
    color: white;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.service-card h3 {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
    text-align: center;
}

.service-price {
    font-size: 20px;
    font-weight: 700;
    color: #667eea;
    text-align: center;
    margin-bottom: 15px;
    padding: 8px 15px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 25px;
    display: inline-block;
    width: 100%;
}

.service-card p {
    color: #666;
    line-height: 1.5;
    text-align: left;
    margin-bottom: 20px;
    font-size: 14px;
}

/* Service Features */
.service-features {
    margin-top: 20px;
}

.feature-row {
    display: flex;
    gap: 10px;
    margin-bottom: 8px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 6px;
    flex: 1;
    font-size: 11px;
    font-weight: 600;
    color: #333;
    background: rgba(102, 126, 234, 0.05);
    padding: 6px 8px;
    border-radius: 15px;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.feature-item i {
    color: #667eea;
    font-size: 10px;
    flex-shrink: 0;
}

.feature-item span {
    line-height: 1.2;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Services Grid Responsive */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

/* Why Choose Us Section */
.why-choose-us {
    padding: 80px 0;
    background: #f8f9fa;
}

.why-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.why-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.why-text h2 {
    font-size: 36px;
    font-weight: 700;
    color: #333;
    margin-bottom: 40px;
}

.feature-list {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.feature-item {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.feature-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    flex-shrink: 0;
}

.feature-text h4 {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.feature-text p {
    color: #666;
    line-height: 1.5;
}

.why-image {
    position: relative;
}

.image-wrapper {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.image-wrapper img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.floating-card {
    position: absolute;
    bottom: 20px;
    right: 20px;
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.card-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-content i {
    font-size: 24px;
    color: #667eea;
}

.card-content strong {
    display: block;
    font-weight: 600;
    color: #333;
}

.card-content span {
    font-size: 14px;
    color: #666;
}

/* CTA Section */
.cta-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
}

.cta-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.cta-content h2 {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 20px;
}

.cta-content p {
    font-size: 18px;
    margin-bottom: 40px;
    opacity: 0.9;
}

.cta-actions {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-section .btn-primary {
    background: white;
    color: #667eea;
}

.cta-section .btn-primary:hover {
    background: #f8f9fa;
    color: #667eea;
}

.cta-section .btn-outline {
    border-color: white;
    color: white;
}

.cta-section .btn-outline:hover {
    background: white;
    color: #667eea;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .hero-content,
    .why-content {
        grid-template-columns: 1fr;
        gap: 40px;
        text-align: center;
    }
    
    .hero-title {
        font-size: 40px;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .image-grid {
        height: 400px;
    }

    .services-grid {
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 25px;
    }
    
    .service-card {
        padding: 25px 20px;
    }
    
    .service-icon {
        width: 70px;
        height: 70px;
        font-size: 26px;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 60px 0;
        min-height: auto;
    }
    
    .hero-title {
        font-size: 32px;
    }
    
    .hero-description {
        font-size: 16px;
    }
    
    .hero-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-stats {
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .service-card {
        padding: 25px 20px;
    }

    .service-card h3 {
        font-size: 20px;
    }

    .service-price {
        font-size: 18px;
    }
    
    
    .section-header h2,
    .why-text h2,
    .cta-content h2 {
        font-size: 28px;
    }
    
    .feature-list {
        gap: 20px;
    }
    
    .feature-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }

    .feature-row {
        flex-direction: column;
        gap: 6px;
    }
    
    .feature-item {
        font-size: 12px;
        padding: 8px 10px;
    }
    
    .services-preview,
    .why-choose-us,
    .cta-section {
        padding: 60px 0;
    }
    
    .cta-actions {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .hero-container,
    .services-container,
    .why-container,
    .cta-container {
        padding: 0 15px;
    }
    
    .hero-title {
        font-size: 28px;
    }
    
    .btn-primary,
    .btn-secondary,
    .btn-outline {
        padding: 12px 20px;
        font-size: 15px;
    }
    
    .btn-primary.large,
    .btn-outline.large {
        padding: 15px 25px;
        font-size: 16px;
    }
    
    .image-grid {
        height: 300px;
        gap: 10px;
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
        font-size: 24px;
    }

    .service-card {
        padding: 20px 15px;
    }
    
    .service-badge {
        padding: 6px 15px;
        font-size: 11px;
    }
    
    .feature-item {
        font-size: 11px;
        padding: 6px 8px;
    }
    
    .floating-card {
        bottom: 10px;
        right: 10px;
        padding: 15px;
    }
}
</style>
@endsection