{{-- filepath: resources/views/admin/layanan/index.blade.php --}}
@extends('adminlte::page')
@section('title', 'Kelola Layanan')
@section('content_header')
    <h1>Kelola Layanan</h1>
@stop

@section('content')
<a href="{{ route('admin.layanan.create') }}" class="btn btn-primary mb-3">Tambah Layanan</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Layanan</th>
            <th>Harga</th>
            <th>Estimasi Pengerjaan per Kucing (menit)</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($layanans as $layanan)
        <tr>
            <td>{{ $layanan->id }}</td>
            <td>{{ $layanan->nama_layanan }}</td>
            <td>{{ $layanan->harga }}</td>
            <td>{{ $layanan->estimasi_pengerjaan_per_kucing }}</td>
            <td>
                <a href="{{ route('admin.layanan.edit', $layanan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.layanan.destroy', $layanan->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus layanan ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop