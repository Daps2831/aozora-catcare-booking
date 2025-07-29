{{-- filepath: resources/views/admin/layanan/edit.blade.php --}}
@extends('adminlte::page')
@section('title', 'Edit Layanan')
@section('content_header')
    <h1>Edit Layanan</h1>
@stop

@section('content')
<form action="{{ route('admin.layanan.update', $layanan->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nama Layanan</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $layanan->nama) }}" required>
    </div>
    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" value="{{ old('harga', $layanan->harga) }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@stop