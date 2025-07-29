{{-- filepath: resources/views/admin/kucing/edit.blade.php --}}
@extends('adminlte::page')
@section('title', 'Edit Kucing')
@section('content_header')
    <h1>Edit Kucing untuk {{ $user->name }}</h1>
@stop

@section('content')
<form action="{{ route('admin.users.kucing.update', [$user->id, $kucing->id]) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nama Kucing</label>
        <input type="text" name="nama_kucing" class="form-control" value="{{ old('nama_kucing', $kucing->nama_kucing) }}" required>
    </div>
    <div class="form-group">
        <label>Jenis</label>
        <input type="text" name="jenis" class="form-control" value="{{ old('jenis', $kucing->jenis) }}" required>
    </div>
    <div class="form-group">
        <label>Umur</label>
        <input type="number" name="umur" class="form-control" value="{{ old('umur', $kucing->umur) }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">Kembali</a>
</form>
@stop