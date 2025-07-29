{{-- filepath: resources/views/admin/groomer/edit.blade.php --}}
@extends('adminlte::page')
@section('title', 'Edit Groomer')
@section('content_header')
    <h1>Edit Groomer</h1>
@stop

@section('content')
<form action="{{ route('admin.groomer.update', $groomer->id_groomer) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nama Groomer</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $groomer->nama) }}" required>
    </div>
    <div class="form-group">
        <label>No HP</label>
        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $groomer->no_hp) }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.groomer.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@stop