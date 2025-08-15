
@extends('layouts.app')

@section('title', 'Layanan Kami')

@section('content')
<div class="service-container">
    <!-- Header Section -->
    <div class="service-header">
        <div class="header-icon">
            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <h1>Layanan Grooming Kami</h1>
        <p class="header-description">
            Kami menyediakan berbagai layanan grooming profesional untuk kucing kesayangan Anda. 
            Setiap layanan dilakukan dengan peralatan berkualitas tinggi dan tim yang berpengalaman.
        </p>
    </div>

    <!-- Services Grid -->
    <div class="services-grid">
        <div class="service-card">
            <div class="service-image">
                <img src="{{ asset('images/exclusive.jpg') }}" alt="Layanan Ekslusif">
                <div class="service-overlay">
                    <span class="service-badge premium">Premium</span>
                </div>
            </div>
            <div class="service-content">
                <h3>Layanan Eksklusif</h3>
                <p>Paket lengkap dengan perawatan premium untuk kucing kesayangan Anda. </p>
      
            </div>
        </div>

        <div class="service-card">
            <div class="service-image">
                <img src="{{ asset('images/grooming1.jpg') }}" alt="Layanan Grooming 1">
                <div class="service-overlay">
                    <span class="service-badge popular">Populer</span>
                </div>
            </div>
            <div class="service-content">
                <h3>Grooming Basic</h3>
                <p>Paket grooming dasar yang mencakup mandi, blow dry, dan basic trimming untuk menjaga kebersihan kucing.</p>
         
            </div>
        </div>

        <div class="service-card">
            <div class="service-image">
                <img src="{{ asset('images/grooming2.jpg') }}" alt="Layanan Grooming 2">
            </div>
            <div class="service-content">
                <h3>Grooming Standard</h3>
                <p>Paket grooming menengah dengan styling dan perawatan yang lebih detail untuk penampilan kucing yang maksimal.</p>
          
            </div>
        </div>

        <div class="service-card">
            <div class="service-image">
                <img src="{{ asset('images/addition.jpg') }}" alt="Layanan Tambahan">
                <div class="service-overlay">
                    <span class="service-badge addon">Add-on</span>
                </div>
            </div>
            <div class="service-content">
                <h3>Layanan Tambahan</h3>
                <p>Berbagai layanan tambahan yang dapat dikombinasikan dengan paket grooming utama sesuai kebutuhan.</p>
         
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="service-cta">
        <h2>Siap untuk Booking?</h2>
        <p>Pilih layanan terbaik untuk kucing kesayangan Anda dan buat appointment sekarang!</p>
        <div class="cta-buttons">
            <a href="{{ route('booking.index') }}" class="btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="4"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                Buat Booking
            </a>
            <a href="{{ route('user.dashboard') }}" class="btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.service-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Header Styles */
.service-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    color: white;
    position: relative;
    overflow: hidden;
}

.service-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="10" cy="10" r="2"/></g></svg>');
    opacity: 0.3;
}

.header-icon {
    position: relative;
    z-index: 1;
    margin-bottom: 1rem;
}

.service-header h1 {
    position: relative;
    z-index: 1;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-description {
    position: relative;
    z-index: 1;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
    opacity: 0.95;
    line-height: 1.6;
}

/* Services Grid */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.service-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.service-image {
    position: relative;
    height: 320px;
    overflow: hidden;
}

.service-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
    min-width: 100%; /* Pastikan lebar minimum 100% */
    min-height: 100%; /* Pastikan tinggi minimum 100% */
}

.service-card:hover .service-image img {
    transform: scale(1.05);
}

.service-overlay {
    position: absolute;
    top: 12px;
    right: 12px;
}

.service-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.service-badge.premium {
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    color: #8b5cf6;
}

.service-badge.popular {
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    color: white;
}

.service-badge.addon {
    background: linear-gradient(45deg, #4facfe, #00f2fe);
    color: white;
}

.service-content {
    padding: 1.5rem;
}

.service-content h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.8rem;
    color: #2d3748;
}

.service-content p {
    color: #718096;
    line-height: 1.6;
    margin-bottom: 1rem;
    font-size: 0.95rem;
}



/* Call to Action */
.service-cta {
    text-align: center;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    color: white;
    position: relative;
    overflow: hidden;
}

.service-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="10" cy="10" r="2"/></g></svg>');
    opacity: 0.3;
}

.service-cta h2 {
    position: relative;
    z-index: 1;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.service-cta p {
    position: relative;
    z-index: 1;
    font-size: 1.1rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-buttons {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn-primary {
    background: white;
    color: #667eea;
}

.btn-primary:hover {
    background: #f7fafc;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-secondary {
    background: transparent;
    color: white;
    border-color: rgba(255,255,255,0.3);
}

.btn-secondary:hover {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.5);
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .service-container {
        padding: 1rem;
    }
    
    .service-header h1 {
        font-size: 2rem;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        max-width: 280px;
        justify-content: center;
    }
}

#image-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 16px;
    max-width: 80vw;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalFadeIn 0.3s ease;
    /* Hapus margin: auto; */
}

@keyframes modalFadeIn {
    from { 
        opacity: 0; 
        transform: scale(0.9); 
    }
    to { 
        opacity: 1; 
        transform: scale(1); 
    }
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 24px;
    cursor: pointer;
    z-index: 10;
    transition: background 0.3s ease;
}

.modal-close:hover {
    background: rgba(0, 0, 0, 0.7);
}

.modal-image-container {
    width: 100%;
    max-height: 70vh;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f8f9fa;
}

#modal-image {
    width: 100%;
    height: auto;
    max-height: 70vh;
    object-fit: contain;
}

.modal-description {
    padding: 2rem;
    text-align: center;
}

.modal-description h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #2d3748;
}

.modal-description p {
    font-size: 1.1rem;
    color: #718096;
    line-height: 1.6;
    margin-bottom: 0;
}

/* Responsive Modal */
@media (max-width: 768px) {
    .modal-content {
        max-width: 95vw;
        max-height: 90vh;
        margin: 10px;
    }
    
    .modal-description {
        padding: 1.5rem;
    }
    
    .modal-description h3 {
        font-size: 1.5rem;
    }
}
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Buat modal element
    const modal = document.createElement('div');
    modal.id = 'image-modal';
    modal.innerHTML = `
        <div class="modal-overlay">
            <div class="modal-content">
                <button class="modal-close">&times;</button>
                <div class="modal-image-container">
                    <img id="modal-image" src="" alt="">
                </div>
                <div class="modal-description">
                    <h3 id="modal-title"></h3>
                    <p id="modal-text"></p>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Event listener untuk semua gambar service
    const serviceImages = document.querySelectorAll('.service-image img');
    serviceImages.forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
            const card = this.closest('.service-card');
            const title = card.querySelector('.service-content h3').textContent;
            const description = card.querySelector('.service-content p').textContent;
            
            // Set modal content
            document.getElementById('modal-image').src = this.src;
            document.getElementById('modal-image').alt = this.alt;
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-text').textContent = description;
            
            // Show modal
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    });

    // Close modal events
    const closeModal = () => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    modal.querySelector('.modal-close').addEventListener('click', closeModal);
    modal.querySelector('.modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // Close with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closeModal();
        }
    });
});
</script>
@endsection