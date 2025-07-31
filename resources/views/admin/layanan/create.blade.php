{{-- filepath: resources/views/admin/layanan/create.blade.php --}}
@extends('adminlte::page')
@section('title', 'Tambah Layanan')
@section('content_header')
    <h1>Tambah Layanan</h1>
@stop
@section('content')
<form action="{{ route('admin.layanan.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Nama Layanan</label>
        <input type="text" name="nama_layanan" class="form-control" value="{{ old('nama_layanan') }}" required>
    </div>
    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" required>
    </div>
    <div class="form-group">
        <label>Estimasi Pengerjaan per Kucing (menit)</label>
        <input type="number" name="estimasi_pengerjaan_per_kucing" class="form-control" value="{{ old('estimasi_pengerjaan_per_kucing') }}" required>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@stop