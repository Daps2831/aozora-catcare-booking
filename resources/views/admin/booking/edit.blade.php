@extends('adminlte::page')
@section('title', 'Edit Booking')
@section('content_header')
    <h1>Edit Booking</h1>
@stop

@section('content')
{{-- Alert untuk error --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Info current booking --}}
<div class="alert alert-info">
    <strong>Info Booking Saat Ini:</strong><br>
    Tanggal: {{ $booking->tanggalBooking }} | Jam: {{ $booking->jamBooking }} | 
    Jumlah Kucing: {{ $booking->kucings->count() }} | 
    Estimasi: {{ $booking->estimasi ?? 0 }} menit
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Customer</label>
                        <input type="text" class="form-control" value="{{ $booking->customer->name }}" readonly>
                        <input type="hidden" name="customer_id" value="{{ $booking->customer_id }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggalBooking" class="form-label">Tanggal Booking</label>
                        <input type="date" name="tanggalBooking" class="form-control @error('tanggalBooking') is-invalid @enderror" 
                               value="{{ old('tanggalBooking', $booking->tanggalBooking) }}" required>
                        @error('tanggalBooking')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jamBooking" class="form-label">Jam Booking</label>
                        <input type="time" name="jamBooking" class="form-control @error('jamBooking') is-invalid @enderror" 
                               value="{{ old('jamBooking', $booking->jamBooking) }}" required>
                        @error('jamBooking')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alamatBooking" class="form-label">Alamat Booking</label>
                        <textarea name="alamatBooking" class="form-control" rows="2">{{ old('alamatBooking', $booking->alamatBooking) }}</textarea>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Kucing & Layanan</h5>
                <small class="text-muted">Maksimal 10 kucing per hari</small>
            </div>
            
            @error('kucings')
                <div class="alert alert-warning">{{ $message }}</div>
            @enderror

            <div id="kucing-list">
                @foreach($booking->kucings as $i => $kucing)
                    <div class="row mb-2 kucing-row">
                        <div class="col-md-6">
                            <label class="form-label">Kucing</label>
                            <select name="kucings[{{ $i }}][id]" class="form-control" required>
                                @foreach($allKucings as $kc)
                                    <option value="{{ $kc->id }}" {{ $kc->id == $kucing->id ? 'selected' : '' }}>
                                        {{ $kc->nama_kucing }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Layanan</label>
                            <select name="kucings[{{ $i }}][layanan_id]" class="form-control" required>
                                @foreach($layanans as $layanan)
                                    <option value="{{ $layanan->id }}" 
                                            {{ $layanan->id == $kucing->pivot->layanan_id ? 'selected' : '' }}
                                            data-estimasi="{{ $layanan->estimasi_pengerjaan_per_kucing }}">
                                        {{ $layanan->nama_layanan }} ({{ $layanan->estimasi_pengerjaan_per_kucing }} menit)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeKucingRow(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div id="new-kucing-list"></div>
            
            <div class="mb-3">
                <button type="button" class="btn btn-success" onclick="addKucingRow()">
                    <i class="fas fa-plus"></i> Tambah Kucing
                </button>
                <span class="ml-3 text-muted">Total Estimasi: <span id="total-estimasi">{{ $booking->estimasi ?? 0 }}</span> menit</span>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Booking
                </button>
                <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
let kucingIndex = {{ count($booking->kucings) }};

function addKucingRow() {
    let html = `
    <div class="row mb-2 kucing-row">
        <div class="col-md-6">
            <label class="form-label">Kucing</label>
            <select name="kucings[${kucingIndex}][id]" class="form-control" required>
                @foreach($allKucings as $kc)
                    <option value="{{ $kc->id }}">{{ $kc->nama_kucing }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5">
            <label class="form-label">Layanan</label>
            <select name="kucings[${kucingIndex}][layanan_id]" class="form-control" required onchange="updateEstimasi()">
                @foreach($layanans as $layanan)
                    <option value="{{ $layanan->id }}" data-estimasi="{{ $layanan->estimasi_pengerjaan_per_kucing }}">
                        {{ $layanan->nama_layanan }} ({{ $layanan->estimasi_pengerjaan_per_kucing }} menit)
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeKucingRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    `;
    document.getElementById('new-kucing-list').insertAdjacentHTML('beforeend', html);
    kucingIndex++;
    updateEstimasi();
}

function removeKucingRow(btn) {
    btn.closest('.kucing-row').remove();
    updateEstimasi();
}

function updateEstimasi() {
    let total = 0;
    document.querySelectorAll('select[name*="[layanan_id]"]').forEach(function(select) {
        let estimasi = select.options[select.selectedIndex].dataset.estimasi || 0;
        total += parseInt(estimasi);
    });
    document.getElementById('total-estimasi').textContent = total;
}

// Update estimasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Add onchange event to existing selects
    document.querySelectorAll('select[name*="[layanan_id]"]').forEach(function(select) {
        select.addEventListener('change', updateEstimasi);
    });
    updateEstimasi();
});
</script>
@stop