{{-- filepath: resources/views/admin/users/index.blade.php --}}
@extends('adminlte::page')
@section('title', 'Kelola User')
@section('content_header')
    <h1>Kelola User</h1>
@stop

@section('content')
<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Tambah User</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm">Lihat</a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop