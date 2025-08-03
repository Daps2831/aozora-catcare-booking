@extends('adminlte::page')
@section('title', 'Edit Booking')
@section('content_header')
    <h1>Edit Booking</h1>
@stop

@if($errors->has('kucings'))
    <div class="alert alert-danger">{{ $errors->first('kucings') }}</div>
@endif

@section('content')
<form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Customer</label>
        <input type="text" class="form-control" value="{{ $booking->customer->name }}" readonly>
        <input type="hidden" name="customer_id" value="{{ $booking->customer_id }}">
    </div>
    <div class="mb-3">
        <label for="tanggalBooking" class="form-label">Tanggal Booking</label>
        <input type="date" name="tanggalBooking" class="form-control" value="{{ $booking->tanggalBooking }}" required>
    </div>
    <div class="mb-3">
        <label for="jamBooking" class="form-label">Jam Booking</label>
        <input type="time" name="jamBooking" class="form-control" value="{{ $booking->jamBooking }}" required>
    </div>
    <div class="mb-3">
        <label for="alamatBooking" class="form-label">Alamat Booking</label>
        <textarea name="alamatBooking" class="form-control" rows="2">{{ $booking->alamatBooking }}</textarea>
    </div>
    <hr>
    <h5>Kucing & Layanan</h5>
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
                            <option value="{{ $layanan->id }}" {{ $layanan->id == $kucing->pivot->layanan_id ? 'selected' : '' }}>
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeKucingRow(this)">Hapus</button>
                </div>
            </div>
        @endforeach
    </div>
    <div id="new-kucing-list"></div>
    <button type="button" class="btn btn-success mb-3" onclick="addKucingRow()">+ Tambah Kucing</button>
    <button type="submit" class="btn btn-primary">Update Booking</button>
    <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">Kembali</a>
</form>
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
            <select name="kucings[${kucingIndex}][layanan_id]" class="form-control" required>
                @foreach($layanans as $layanan)
                    <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeKucingRow(this)">Hapus</button>
        </div>
    </div>
    `;
    document.getElementById('new-kucing-list').insertAdjacentHTML('beforeend', html);
    kucingIndex++;
}

function removeKucingRow(btn) {
    btn.closest('.kucing-row').remove();
}
</script>
@stop