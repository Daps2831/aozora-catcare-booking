{{-- filepath: resources/views/admin/kucing/create.blade.php --}}
@extends('adminlte::page')
@section('title', 'Tambah Kucing')
@section('content_header')
    <h1>Tambah Kucing untuk {{ $user->name }}</h1>
@stop

@section('content')
<form action="{{ route('admin.users.kucing.store', $user->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Nama Kucing</label>
        <input type="text" name="nama_kucing" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Jenis</label>
        <input type="text" name="jenis" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Umur</label>
        <input type="number" name="umur" class="form-control" required>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">Kembali</a>
</form>
@stop