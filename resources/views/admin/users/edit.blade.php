{{-- filepath: resources/views/admin/users/edit.blade.php --}}
@extends('adminlte::page')
@section('title', 'Edit User')
@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')
<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@stop