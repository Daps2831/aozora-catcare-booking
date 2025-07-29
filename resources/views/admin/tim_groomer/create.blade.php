{{-- filepath: resources/views/admin/tim_groomer/create.blade.php --}}
@extends('adminlte::page')
@section('title', 'Tambah Tim Grooming')
@section('content_header')
    <h1>Tambah Tim Grooming</h1>
@stop
@section('content')
<form action="{{ route('admin.tim-groomer.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Anggota 1</label>
        <select name="anggota_1" class="form-control" required>
            <option value="">Pilih Groomer</option>
            @foreach($groomers as $g)
                <option value="{{ $g->id_groomer }}">{{ $g->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Anggota 2 (Opsional)</label>
        <select name="anggota_2" class="form-control">
            <option value="">-- Tidak Ada --</option>
            @foreach($groomers as $g)
                <option value="{{ $g->id_groomer }}" {{ old('anggota_2', $tim_groomer->anggota_2 ?? '') == $g->id_groomer ? 'selected' : '' }}>
                    {{ $g->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.tim-groomer.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@stop