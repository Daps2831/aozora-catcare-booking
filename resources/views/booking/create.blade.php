
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
            
            <!-- Time Selection Section - PERBAIKAN -->
            <div class="form-section">
                <div class="section-header">
                    <h3><i class="fas fa-clock"></i> Pilih Waktu Booking</h3>
                </div>
                <div class="form-content">
                    <div class="time-selector">
                        <div class="time-inputs">
                            <div class="slider-container">
                                <label for="jamBookingSlider">Geser untuk memilih jam:</label>
                                <!-- PERBAIKAN: Step yang lebih halus untuk precision tinggi -->
                                <input type="range" id="jamBookingSlider" 
                                    min="8" max="18.5" step="0.01" value="8.0">
                            </div>
                            <div class="time-input-container">
                                <label for="jamBookingManual">Atau ketik manual:</label>
                                <!-- PERBAIKAN: Hilangkan semua constraint yang membingungkan -->
                                <input type="time" id="jamBookingManual" name="jamBooking" 
                                    value="08:00" required>
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
                                <span>Booking minimal 2 jam dari sekarang</span>
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
                        <!-- Vertical Scroll Container untuk lebih dari 5 kucing -->
                        <div class="cats-scroll-wrapper">
                            <div class="scroll-header">
                                <div class="scroll-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>{{ $kucings->count() }} kucing tersedia. Gunakan tombol atau scroll untuk melihat semua.</span>
                                </div>
                                <div class="scroll-controls">
                                    <button type="button" class="scroll-btn" id="scroll-up">
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <div class="scroll-indicator-track">
                                        <div class="scroll-indicator" id="scroll-indicator"></div>
                                    </div>
                                    <button type="button" class="scroll-btn" id="scroll-down">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="cats-scroll-container" id="cats-scroll-container">
                                @foreach($kucings as $kucing)
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
                                @endforeach
                            </div>
                            
                            <div class="scroll-footer">
                                <div class="scroll-tips">
                                    <div class="tip-item">
                                        <i class="fas fa-mouse"></i>
                                        <span>Scroll mouse wheel</span>
                                    </div>
                                    <div class="tip-item">
                                        <i class="fas fa-hand-point-up"></i>
                                        <span>Swipe up/down</span>
                                    </div>
                                    <div class="tip-item">
                                        <i class="fas fa-keyboard"></i>
                                        <span>Arrow keys</span>
                                    </div>
                                </div>
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
    cursor: text; /* Better UX for manual input */
}

#jamBookingManual::-webkit-calendar-picker-indicator {
    background-color: #667eea;
    border-radius: 3px;
    cursor: pointer;
}

/* Remove spinners on time input for cleaner look */
#jamBookingManual::-webkit-outer-spin-button,
#jamBookingManual::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
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

/* =============================================== */
/* VERTICAL SCROLL STYLES - REPLACE SLIDER STYLES */
/* =============================================== */

/* TAMBAHAN: Perbaikan untuk container overflow */
.cats-scroll-wrapper {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    background: white;
    overflow: hidden;
    width: 100%;
    max-width: 100%;
}}

.scroll-header {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%);
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}

.scroll-info {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #667eea;
    font-weight: 500;
    font-size: 14px;
}

.scroll-info i {
    font-size: 16px;
}

.scroll-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.scroll-btn {
    background: #667eea;
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
}

.scroll-btn:hover {
    background: #5a67d8;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.scroll-btn:disabled {
    background: #cbd5e0;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.scroll-indicator-track {
    width: 4px;
    height: 60px;
    background: #e2e8f0;
    border-radius: 2px;
    position: relative;
    overflow: hidden;
}

.scroll-indicator {
    width: 100%;
    height: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
    transition: transform 0.3s ease;
    position: absolute;
    top: 0;
}

/* Cats Scroll Container */
.cats-scroll-container {
    position: relative;
    overflow: hidden !important;
    scroll-behavior: smooth;
    padding: 20px;
    background: white;
    width: 100%;
    box-sizing: border-box;
    border-bottom: 0px solid transparent; /* Buffer space */
}

.cats-scroll-container:focus {
    outline: 2px solid #667eea;
    outline-offset: -2px;
}

/* Cat Items in Scroll */
/* PERBAIKI: Cat Items styling untuk konsistensi height */
.cats-scroll-container .cat-item {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
    background: white;
    overflow: visible; /* CHANGED: allow overflow untuk service selection */
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
    position: relative;
}

.cats-scroll-container .cat-item:last-child {
    margin-bottom: 30px; /* UBAH: Tetap berikan margin untuk spacing yang konsisten */
}

.cats-scroll-container .cats-items-wrapper {
    transition: transform 0.3s ease;
    width: 100%;
    position: relative;
    min-height: 100%;
    padding-bottom: 20px; /* Extra padding bottom */
}

.cats-scroll-container .cat-item:hover {
    border-color: #667eea;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
    transform: translateX(5px);
}

.cats-scroll-container .cat-item.selected {
    border-color: #667eea;
    background: #f8f9ff;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.15);
}

/* Enhanced Cat Card for Scroll */
.cats-scroll-container .cat-card {
    display: flex;
    padding: 15px;
    align-items: center;
    cursor: pointer;
    transition: background 0.3s ease;
    position: relative;
}

.cats-scroll-container .cat-card:hover {
    background: #f8f9fa;
}

.cats-scroll-container .cat-selection {
    position: relative;
    flex: 1;
    min-height: 70px; /* Base height for cat selection */
    padding: 15px;
}

.cats-scroll-container .cat-selection input[type="checkbox"] {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 2;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.cats-scroll-container .cat-card {
    padding-left: 45px;
}

.cats-scroll-container .cat-image {
    flex-shrink: 0;
    margin-right: 15px;
}

.cats-scroll-container .cat-image img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.cats-scroll-container .cat-item:hover .cat-image img {
    border-color: #667eea;
}

.cats-scroll-container .cat-info {
    flex: 1;
    min-width: 0;
}

.cats-scroll-container .cat-info h4 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cats-scroll-container .cat-info p {
    margin: 0 0 6px 0;
    color: #666;
    font-size: 13px;
}

.cats-scroll-container .cat-details {
    display: flex;
    align-items: center;
    font-size: 12px;
    color: #888;
    gap: 10px;
}

.cats-scroll-container .cat-details i {
    margin-right: 3px;
    color: #667eea;
}

/* Service Selection in Scroll */
/* Service Selection Height Consistency */
/* FIXED: Service selection dengan proper height */
.cats-scroll-container .service-selection {
    padding: 15px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    transition: all 0.3s ease;
    overflow: visible;
    box-sizing: border-box;
}

.cats-scroll-container .service-selection label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
    font-size: 13px;
}

.cats-scroll-container .service-select {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    transition: all 0.3s ease;
}

.cats-scroll-container .service-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Scroll Footer */
.scroll-footer {
    background: #f8f9fa;
    padding: 15px 20px;
    border-top: 1px solid #e9ecef;
}

.scroll-tips {
    display: flex;
    justify-content: center;
    gap: 25px;
    flex-wrap: wrap;
}

.tip-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #666;
    font-weight: 500;
}

.tip-item i {
    color: #667eea;
    font-size: 14px;
}

/* PERBAIKAN: Responsive yang lebih baik */
@media (max-width: 768px) {
    .scroll-header {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    
    .scroll-controls {
        justify-content: center;
    }
    
    .cats-scroll-container {
        padding: 15px;
        border-bottom: 0px solid transparent;
        /* HAPUS min-height fixed untuk mobile */
    }
    
    .cats-scroll-container .cat-item {
        margin-bottom: 12px !important;
        display: flex !important;
        flex-direction: column !important;
    }
    
    .cats-scroll-container .cat-card {
        padding: 12px !important;
        padding-left: 40px !important;
        display: flex !important;
        align-items: center !important;
        flex-direction: row !important; /* Tetap horizontal */
    }
    
    .cats-scroll-container .cat-selection {
        padding: 12px !important;
    }
    
    .cats-scroll-container .cat-selection input[type="checkbox"] {
        top: 12px !important;
        left: 12px !important;
        width: 16px !important;
        height: 16px !important;
    }
    
    .cats-scroll-container .cat-image {
        flex-shrink: 0 !important;
        margin-right: 12px !important;
    }
    
    .cats-scroll-container .cat-image img {
        width: 55px !important;
        height: 55px !important;
    }

    .cats-scroll-container .cats-items-wrapper {
        padding-bottom: 25px;
    }

    .cats-scroll-container .cat-item:last-child {
        margin-bottom: 30px !important;
    }

    .cats-scroll-container .service-selection {
        padding: 12px !important;
        margin-top: 8px !important;
    }
    
    .cats-scroll-container .service-select {
        padding: 8px 10px !important;
        font-size: 13px !important;
    }
}

@media (max-width: 480px) {
    .cats-scroll-container {
        padding: 10px !important;
    }
    
    .cats-scroll-container .cat-card {
        padding: 10px !important;
        padding-left: 35px !important;
    }
    
    .cats-scroll-container .cat-selection input[type="checkbox"] {
        top: 50% !important;
        left: 8px !important;
        transform: translateY(-50%) !important;
    }
    
    .cats-scroll-container .cat-image img {
        width: 45px !important;
        height: 45px !important;
    }
    
    .cats-scroll-container .cat-info h4 {
        font-size: 14px !important;
    }
    
    .cats-scroll-container .cat-info p {
        font-size: 12px !important;
    }
    
    .cats-scroll-container .cat-details {
        font-size: 11px !important;
    }
    
    .scroll-btn {
        width: 32px !important;
        height: 32px !important;
        font-size: 12px !important;
    }
    
    .scroll-indicator-track {
        height: 40px !important;
    }
    
    .scroll-indicator {
        height: 15px !important;
    }
}

/* Animation for smooth scroll */
@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.cats-scroll-container .cat-item {
    animation: fadeInSlide 0.3s ease-out;
}

/* Enhanced checkbox styling */
.cats-scroll-container .cat-selection input[type="checkbox"] {
    appearance: none;
    background: white;
    border: 2px solid #ddd;
    border-radius: 4px;
    position: relative;
}

.cats-scroll-container .cat-selection input[type="checkbox"]:checked {
    background: #667eea;
    border-color: #667eea;
}

.cats-scroll-container .cat-selection input[type="checkbox"]:checked::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

/* Debug styles (remove in production) */
.cats-scroll-container.debug {
    border: 2px dashed red !important;
}

.cats-scroll-container.debug .cat-item {
    border: 1px solid blue !important;
}

.cats-scroll-container.debug .cats-items-wrapper {
    border: 1px solid green !important;
}

/* PERBAIKAN: Debug mode yang bisa dihapus setelah fix */
.cats-scroll-container.debug-mode {
    border: 2px solid red !important;
}

.cats-scroll-container.debug-mode .cats-items-wrapper {
    border: 1px solid blue !important;
}

.cats-scroll-container.debug-mode .cat-item {
    border: 1px solid green !important;
    position: relative;
}

.cats-scroll-container.debug-mode .cat-item::after {
    content: attr(data-index);
    position: absolute;
    top: 5px;
    right: 5px;
    background: red;
    color: white;
    padding: 2px 6px;
    font-size: 10px;
    border-radius: 3px;
}

/* Smooth transition untuk service selection */
.cats-scroll-container .service-selection {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
}

.cats-scroll-container .cat-item input:checked ~ .cat-card + .service-selection,
.cats-scroll-container .service-selection[style*="display: block"] {
    max-height: 200px; /* Cukup untuk dropdown */
    padding: 15px;
}

@media (max-width: 768px) {
    .cats-scroll-container .cat-item input:checked ~ .cat-card + .service-selection,
    .cats-scroll-container .service-selection[style*="display: block"] {
        max-height: 150px;
        padding: 12px;
    }
}
</style>

<script>
// Debug script untuk mobile - hapus setelah fix
document.addEventListener('DOMContentLoaded', function() {
    const scrollContainer = document.getElementById('cats-scroll-container');
    
    if (scrollContainer) {
        console.log('=== SCROLL CONTAINER DEBUG ===');
        
        setTimeout(() => {
            const wrapper = scrollContainer.querySelector('.cats-items-wrapper');
            const catItems = wrapper ? wrapper.querySelectorAll('.cat-item') : [];
            
            console.log('Container height:', scrollContainer.style.height);
            console.log('Wrapper found:', !!wrapper);
            console.log('Cat items count:', catItems.length);
            
            if (catItems.length > 0) {
                console.log('First item height:', catItems[0].offsetHeight);
                console.log('First item margin:', window.getComputedStyle(catItems[0]).marginBottom);
                
                // Log all item heights
                catItems.forEach((item, index) => {
                    console.log(`Item ${index + 1} height:`, item.offsetHeight);
                });
            }
            
            // Check scroll bounds
            const currentTransform = wrapper ? wrapper.style.transform : 'none';
            console.log('Current transform:', currentTransform);
            
        }, 500);
    }
});
</script>

@endsection