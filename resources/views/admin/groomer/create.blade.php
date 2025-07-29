{{-- filepath: resources/views/admin/groomer/create.blade.php --}}
@extends('adminlte::page')
@section('title', 'Tambah Groomer')
@section('content_header')
    <h1>Tambah Groomer</h1>
@stop

@section('content')
<form action="{{ route('admin.groomer.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Nama Groomer</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="form-group">
        <label>No HP</label>
        <input type="text" name="no_hp" class="form-control" required>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.groomer.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@stop