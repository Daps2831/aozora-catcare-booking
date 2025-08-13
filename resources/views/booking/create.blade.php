
@extends('layouts.app')

@section('title', 'Buat Booking')

@section('content')
<div class="booking-create-section">
    <div class="booking-container">
        <!-- Header Section -->
        <div class="booking-header">
            <div class="header-content">
                <h1><i class="fas fa-calendar-plus"></i> Formulir Booking</h1>
                <div class="booking-date-info">
                    <i class="fas fa-calendar-day"></i>
                    <span>Tanggal: <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <div class="alerts-section">
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                @foreach (array_unique($errors->all()) as $error)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $error }}
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Booking Form -->
        <form id="booking-form" action="{{ route('booking.store') }}" method="POST" class="booking-form">
            @csrf
            <input type="hidden" name="tanggalBooking" value="{{ $selectedDate }}">
            
            <!-- Time Selection Section -->
            <div class="form-section">
                <div class="section-header">
                    <h3><i class="fas fa-clock"></i> Pilih Waktu Booking</h3>
                </div>
                <div class="form-content">
                    <div class="time-selector">
                        <div class="time-inputs">
                            <div class="slider-container">
                                <label for="jamBookingSlider">Geser untuk memilih jam:</label>
                                <input type="range" id="jamBookingSlider" min="8" max="18" step="0.5" value="8">
                            </div>
                            <div class="time-input-container">
                                <label for="jamBookingManual">Atau ketik manual:</label>
                                <input type="time" id="jamBookingManual" name="jamBooking" 
                                       min="08:00" max="18:30" step="600" value="08:00" required>
                            </div>
                        </div>
                        <div class="time-display">
                            <span id="jamBookingLabel">Jam dipilih: 08:00</span>
                        </div>
                    </div>
                    
                    <!-- Operational Hours Info -->
                    <div class="operational-info">
                        <div class="info-item">
                            <i class="fas fa-business-time"></i>
                            <span><strong>Jam Operasional:</strong> 08:00 - 18:30</span>
                        </div>
                        @if(\Carbon\Carbon::parse($selectedDate)->isToday())
                            <div class="info-item warning">
                                <i class="fas fa-info-circle"></i>
                                <span>Booking minimal 2 jam dari sekarang ({{ \Carbon\Carbon::now()->addHours(2)->format('H:i') }})</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address Selection Section -->
            <div class="form-section">
                <div class="section-header">
                    <h3><i class="fas fa-map-marker-alt"></i> Alamat Booking</h3>
                </div>
                <div class="form-content">
                    <div class="address-options">
                        <label class="address-option">
                            <input type="radio" name="alamat_option" id="alamat_default" value="default" checked>
                            <div class="option-content">
                                <div class="option-title">
                                    <i class="fas fa-home"></i>
                                    Gunakan alamat profil
                                </div>
                                <div class="option-desc">{{ $user->customer->alamat ?? 'Alamat belum diatur' }}</div>
                            </div>
                        </label>
                        <label class="address-option">
                            <input type="radio" name="alamat_option" id="alamat_manual" value="manual">
                            <div class="option-content">
                                <div class="option-title">
                                    <i class="fas fa-edit"></i>
                                    Masukkan alamat lain
                                </div>
                                <div class="option-desc">Gunakan alamat berbeda untuk booking ini</div>
                            </div>
                        </label>
                    </div>
                    <div class="address-input">
                        <input type="text" name="alamatBooking" id="alamatBookingInput"
                               placeholder="Masukkan alamat booking lengkap"
                               value="{{ $user->customer->alamat ?? '' }}" required readonly>
                    </div>
                </div>
            </div>

            <!-- Cat Selection Section -->
            <div class="form-section">
                <div class="section-header">
                    <h3><i class="fas fa-cat"></i> Pilih Kucing & Layanan</h3>
                    <div class="section-actions">
                        <label class="select-all-toggle">
                            <input type="checkbox" id="select-all-kucing">
                            <span>Pilih Semua</span>
                        </label>
                    </div>
                </div>
                <div class="form-content">
                    @if($kucings->count() > 5)
                        <!-- Slider Container untuk lebih dari 5 kucing -->
                        <div class="cats-slider-container">
                            <div class="cats-slider" id="cats-slider">
                                @foreach($kucings as $kucing)
                                    <div class="cat-slide">
                                        <div class="cat-item" data-cat-id="{{ $kucing->id }}">
                                            <div class="cat-selection">
                                                <input type="checkbox" name="kucing_ids[]" value="{{ $kucing->id }}"
                                                    id="kucing_{{ $kucing->id }}" class="kucing-checkbox"
                                                    {{ in_array($kucing->id, old('kucing_ids', [])) ? 'checked' : '' }}>
                                                <label for="kucing_{{ $kucing->id }}" class="cat-card">
                                                    <div class="cat-image">
                                                        <img src="{{ $kucing->gambar ? asset('storage/' . $kucing->gambar) : asset('images/no-image-available.png') }}"
                                                            alt="Foto {{ $kucing->nama_kucing }}">
                                                    </div>
                                                    <div class="cat-info">
                                                        <h4>{{ $kucing->nama_kucing }}</h4>
                                                        <p>{{ $kucing->jenis }}</p>
                                                        <div class="cat-details">
                                                            <span><i class="fas fa-birthday-cake"></i> {{ $kucing->umur ?? 'N/A' }} tahun</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="service-selection" id="layanan_container_{{ $kucing->id }}" 
                                                style="{{ in_array($kucing->id, old('kucing_ids', [])) ? '' : 'display:none;' }}">
                                                <label>Pilih Layanan:</label>
                                                <select name="layanan_per_kucing[{{ $kucing->id }}]" class="service-select">
                                                    <option value="">-- Pilih Layanan --</option>
                                                    @foreach($layanans as $layanan)
                                                        <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga }}"
                                                                {{ old('layanan_per_kucing.' . $kucing->id) == $layanan->id ? 'selected' : '' }}>
                                                            {{ $layanan->nama_layanan }} - Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Slider Navigation -->
                            <div class="slider-navigation">
                                <button type="button" class="slider-btn prev-btn" id="prev-cats">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button type="button" class="slider-btn next-btn" id="next-cats">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            <!-- Slider Indicators -->
                            <div class="slider-indicators" id="slider-indicators">
                                <!-- Will be generated by JavaScript -->
                            </div>
                        </div>
                    @else
                        <!-- Normal Grid untuk 5 kucing atau kurang -->
                        <div class="cats-container">
                            @forelse($kucings as $kucing)
                                <div class="cat-item" data-cat-id="{{ $kucing->id }}">
                                    <div class="cat-selection">
                                        <input type="checkbox" name="kucing_ids[]" value="{{ $kucing->id }}"
                                            id="kucing_{{ $kucing->id }}" class="kucing-checkbox"
                                            {{ in_array($kucing->id, old('kucing_ids', [])) ? 'checked' : '' }}>
                                        <label for="kucing_{{ $kucing->id }}" class="cat-card">
                                            <div class="cat-image">
                                                <img src="{{ $kucing->gambar ? asset('storage/' . $kucing->gambar) : asset('images/no-image-available.png') }}"
                                                    alt="Foto {{ $kucing->nama_kucing }}">
                                            </div>
                                            <div class="cat-info">
                                                <h4>{{ $kucing->nama_kucing }}</h4>
                                                <p>{{ $kucing->jenis }}</p>
                                                <div class="cat-details">
                                                    <span><i class="fas fa-birthday-cake"></i> {{ $kucing->umur ?? 'N/A' }} tahun</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="service-selection" id="layanan_container_{{ $kucing->id }}" 
                                        style="{{ in_array($kucing->id, old('kucing_ids', [])) ? '' : 'display:none;' }}">
                                        <label>Pilih Layanan:</label>
                                        <select name="layanan_per_kucing[{{ $kucing->id }}]" class="service-select">
                                            <option value="">-- Pilih Layanan --</option>
                                            @foreach($layanans as $layanan)
                                                <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga }}"
                                                        {{ old('layanan_per_kucing.' . $kucing->id) == $layanan->id ? 'selected' : '' }}>
                                                    {{ $layanan->nama_layanan }} - Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-cat"></i>
                                    </div>
                                    <h4>Belum Ada Kucing Terdaftar</h4>
                                    <p>Anda belum memiliki data kucing yang terdaftar</p>
                                    <a href="{{ route('kucing.register') }}" class="btn-register-cat">
                                        <i class="fas fa-plus"></i>
                                        Daftarkan Kucing Sekarang
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>

            <!-- Total Price Section -->
            <div class="price-summary">
                <div class="price-content">
                    <span class="price-label">Total Harga:</span>
                    <span id="hargaTotal" class="price-amount">Rp 0</span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('booking.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Pilih Tanggal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Kirim Booking
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
/* =============================================== */
/* BOOKING CREATE PAGE STYLES */
/* =============================================== */

.booking-create-section {
    min-height: calc(100vh - 140px);
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 30px 20px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

.booking-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 800px;
    overflow: hidden;
    position: relative;
}

.booking-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Header Section */
.booking-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    text-align: center;
}

.header-content h1 {
    margin: 0 0 15px 0;
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.booking-date-info {
    background: rgba(255,255,255,0.2);
    padding: 12px 20px;
    border-radius: 25px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}

/* Alerts Section */
.alerts-section {
    padding: 0 30px;
    padding-top: 20px;
}

.alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    border: none;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

/* Form Styles */
.booking-form {
    padding: 30px;
}

.form-section {
    margin-bottom: 35px;
    border: 1px solid #e9ecef;
    border-radius: 15px;
    overflow: hidden;
    background: #f8f9fa;
}

.section-header {
    background: white;
    padding: 20px 25px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.section-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header i {
    color: #667eea;
    font-size: 20px;
}

.form-content {
    padding: 25px;
    background: white;
}

/* Time Selection Styles */
.time-selector {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    align-items: start;
}

.time-inputs {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.slider-container,
.time-input-container {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.slider-container label,
.time-input-container label {
    font-weight: 600;
    color: #555;
    font-size: 14px;
}

#jamBookingSlider {
    width: 100%;
    height: 8px;
    border-radius: 5px;
    background: #ddd;
    outline: none;
    -webkit-appearance: none;
}

#jamBookingSlider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #667eea;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
}

#jamBookingManual {
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    font-size: 16px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

#jamBookingManual:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.time-display {
    background: #667eea;
    color: white;
    padding: 15px 20px;
    border-radius: 12px;
    text-align: center;
    font-weight: 600;
    font-size: 18px;
}

.operational-info {
    margin-top: 20px;
    padding: 15px;
    background: #e3f2fd;
    border-radius: 10px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    font-size: 14px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item i {
    color: #2196f3;
    width: 16px;
}

.info-item.warning {
    color: #d32f2f;
    font-weight: 500;
}

.info-item.warning i {
    color: #d32f2f;
}

/* Address Selection Styles */
.address-options {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 20px;
}

.address-option {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.address-option:hover {
    border-color: #667eea;
    background: #f8f9ff;
}

.address-option input[type="radio"] {
    margin-top: 5px;
}

.address-option input[type="radio"]:checked + .option-content {
    color: #667eea;
}

.option-content {
    flex: 1;
}

.option-title {
    font-weight: 600;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.option-desc {
    font-size: 14px;
    color: #666;
    line-height: 1.4;
}

.address-input input {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #e1e5e9;
    border-radius: 12px;
    font-size: 16px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.address-input input:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.address-input input[readonly] {
    background: #e9ecef;
    cursor: not-allowed;
}

/* Cat Selection Styles */
.section-actions {
    display: flex;
    align-items: center;
}

.select-all-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    color: #667eea;
}

.cats-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.cat-item {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.cat-item:hover {
    border-color: #667eea;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
}

.cat-item.selected {
    border-color: #667eea;
    background: #f8f9ff;
}

.cat-selection {
    position: relative;
}

.cat-selection input[type="checkbox"] {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 2;
    width: 20px;
    height: 20px;
}

.cat-card {
    display: flex;
    padding: 20px;
    padding-left: 50px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.cat-card:hover {
    background: #f8f9fa;
}

.cat-image {
    flex-shrink: 0;
    margin-right: 20px;
}

.cat-image img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e9ecef;
}

.cat-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.cat-info h4 {
    margin: 0 0 5px 0;
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.cat-info p {
    margin: 0 0 8px 0;
    color: #666;
    font-size: 14px;
}

.cat-details {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 13px;
    color: #888;
}

.cat-details i {
    margin-right: 4px;
}

.service-selection {
    padding: 20px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.service-selection label {
    display: block;
    font-weight: 600;
    margin-bottom: 10px;
    color: #555;
}

.service-select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    font-size: 15px;
    background: white;
    transition: all 0.3s ease;
}

.service-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.empty-icon {
    font-size: 60px;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h4 {
    margin: 0 0 10px 0;
    font-size: 20px;
    color: #333;
}

.empty-state p {
    margin: 0 0 25px 0;
    font-size: 16px;
}

.btn-register-cat {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-register-cat:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    color: white;
    text-decoration: none;
}

/* Price Summary */
.price-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin: 30px -30px 30px -30px;
    padding: 25px 30px;
    color: white;
    text-align: center;
}

.price-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    font-size: 20px;
    font-weight: 600;
}

.price-amount {
    font-size: 24px;
    font-weight: 700;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 15px;
    justify-content: space-between;
    flex-wrap: wrap;
}

.btn-primary,
.btn-secondary {
    flex: 1;
    min-width: 200px;
    padding: 15px 25px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
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

.btn-primary:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
    opacity: 0.6;
}

.btn-secondary {
    background: white;
    color: #333;
    border: 2px solid #e9ecef;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.btn-secondary:hover {
    background: #f8f9fa;
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
    text-decoration: none;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .booking-container {
        max-width: 100%;
        margin: 0 15px;
        border-radius: 15px;
    }
    
    .time-selector {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .booking-create-section {
        padding: 20px 10px;
        min-height: calc(100vh - 120px);
    }
    
    .booking-container {
        border-radius: 12px;
        margin: 0;
    }
    
    .booking-header {
        padding: 25px 20px;
    }
    
    .header-content h1 {
        font-size: 24px;
        flex-direction: column;
        gap: 8px;
    }
    
    .booking-date-info {
        padding: 10px 15px;
        font-size: 14px;
    }
    
    .booking-form {
        padding: 20px;
    }
    
    .section-header {
        padding: 15px 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .form-content {
        padding: 20px;
    }
    
    .address-options {
        gap: 12px;
    }
    
    .address-option {
        padding: 15px;
        flex-direction: column;
        gap: 10px;
    }
    
    .cat-card {
        flex-direction: column;
        text-align: center;
        padding: 15px;
        padding-top: 45px;
    }
    
    .cat-image {
        margin: 0 auto 15px auto;
    }
    
    .cat-selection input[type="checkbox"] {
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .form-actions {
        flex-direction: column;
        gap: 12px;
    }
    
    .btn-primary,
    .btn-secondary {
        min-width: 100%;
    }
    
    .price-summary {
        margin: 25px -20px 25px -20px;
        padding: 20px;
    }
    
    .price-content {
        font-size: 18px;
        flex-direction: column;
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .section-header h3 {
        font-size: 16px;
    }
    
    .cat-info h4 {
        font-size: 16px;
    }
    
    .btn-primary,
    .btn-secondary {
        padding: 12px 20px;
        font-size: 15px;
    }
    
    .time-inputs {
        gap: 15px;
    }
    
    #jamBookingManual {
        padding: 10px 12px;
        font-size: 16px; /* Prevent iOS zoom */
    }
}

/* Validation Styles */
input:invalid {
    border-color: #dc3545 !important;
}

.error-message {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.error-message::before {
    content: '⚠';
    font-size: 14px;
}

/* Loading States */
.btn-primary.loading {
    pointer-events: none;
    opacity: 0.7;
}

.btn-primary.loading::after {
    content: '';
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-left: 8px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Cat Slider Styles */
.cats-slider-container {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
}

.cats-slider {
    display: flex;
    transition: transform 0.3s ease;
    width: calc(100% * var(--total-slides, 1));
}

.cat-slide {
    flex: 0 0 50%; /* 2 kucing per slide */
    padding: 0 10px;
    box-sizing: border-box;
}

.cat-slide .cat-item {
    margin-bottom: 0;
}

/* Slider Navigation */
.slider-navigation {
    position: absolute;
    top: 50%;
    width: 100%;
    display: flex;
    justify-content: space-between;
    pointer-events: none;
    z-index: 10;
}

.slider-btn {
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    pointer-events: auto;
    color: #667eea;
    font-size: 18px;
}

.slider-btn:hover {
    background: white;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

.slider-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.slider-btn.prev-btn {
    margin-left: -22px;
}

.slider-btn.next-btn {
    margin-right: -22px;
}

/* Slider Indicators */
.slider-indicators {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
    padding: 15px 0;
}

.indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: none;
    background: #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
}

.indicator.active {
    background: #667eea;
    transform: scale(1.2);
}

.indicator:hover {
    background: #764ba2;
}

/* Responsive Slider */
@media (max-width: 768px) {
    .cat-slide {
        flex: 0 0 100%; /* 1 kucing per slide di mobile */
    }
    
    .slider-btn {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .slider-btn.prev-btn {
        margin-left: -20px;
    }
    
    .slider-btn.next-btn {
        margin-right: -20px;
    }
}

@media (max-width: 480px) {
    .slider-btn {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .slider-btn.prev-btn {
        margin-left: -17px;
    }
    
    .slider-btn.next-btn {
        margin-right: -17px;
    }
    
    .indicator {
        width: 10px;
        height: 10px;
    }
}

/* Enhanced Cat Items for Slider */
.cats-slider-container .cat-item {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.cats-slider-container .cat-item:hover {
    border-color: #667eea;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
    transform: translateY(-5px);
}

.cats-slider-container .cat-item.selected {
    border-color: #667eea;
    background: #f8f9ff;
}

.cats-slider-container .service-selection {
    margin-top: auto;
}
</style>
@endsection