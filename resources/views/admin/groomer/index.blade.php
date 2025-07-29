{{-- filepath: resources/views/admin/groomer/index.blade.php --}}
@extends('adminlte::page')
@section('title', 'Kelola Groomer')
@section('content_header')
    <h1>Kelola Groomer</h1>
@stop

@section('content')
<a href="{{ route('admin.groomer.create') }}" class="btn btn-primary mb-3">Tambah Groomer</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($groomers as $groomer)
        <tr>
            <td>{{ $groomer->id_groomer }}</td>
            <td>{{ $groomer->nama }}</td>
            <td>{{ $groomer->no_hp }}</td>
            <td>
                <a href="{{ route('admin.groomer.edit', $groomer->id_groomer) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.groomer.destroy', $groomer->id_groomer) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus groomer ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop