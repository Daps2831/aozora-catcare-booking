{{-- filepath: resources/views/admin/tim_groomer/index.blade.php --}}
@extends('adminlte::page')
@section('title', 'Kelola Tim Grooming')
@section('content_header')
    <h1>Kelola Tim Grooming</h1>
@stop
@section('content')
<a href="{{ route('admin.tim-groomer.create') }}" class="btn btn-primary mb-3">Tambah Tim</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID Tim</th>
            <th>Nama Tim</th> <!-- Tambahkan kolom ini -->
            <th>Anggota 1</th>
            <th>Anggota 2</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tim as $t)
        <tr>
            <td>{{ $t->id_tim }}</td>
            <td>{{ $t->nama_tim }}</td> <!-- Tampilkan nama tim -->
            <td>{{ $t->anggota1->nama ?? '-' }}</td>
            <td>{{ $t->anggota2->nama ?? '-' }}</td>
            <td>
                <a href="{{ route('admin.tim-groomer.edit', $t->id_tim) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.tim-groomer.destroy', $t->id_tim) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus tim ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop