{{-- filepath: resources/views/admin/users/show.blade.php --}}
@extends('adminlte::page')
@section('title', 'Detail User')
@section('content_header')
    <h1>Detail User: {{ $user->name }}</h1>
@stop

@section('content')
<div class="mb-3">
    <strong>Email:</strong> {{ $user->email }}
</div>

<h4>Kucing User</h4>
<a href="{{ route('admin.users.kucing.create', $user->id) }}" class="btn btn-primary mb-2">Tambah Kucing</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Kucing</th>
            <th>Jenis</th>
            <th>Usia</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($user->customer->kucings as $kucing)
        <tr>
            <td>{{ $kucing->nama_kucing }}</td>
            <td>{{ $kucing->jenis }}</td>
            <td>{{ $kucing->umur }}</td>
            <td>
                <a href="{{ route('admin.users.kucing.edit', [$user->id, $kucing->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.users.kucing.destroy', [$user->id, $kucing->id]) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus kucing ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
@stop