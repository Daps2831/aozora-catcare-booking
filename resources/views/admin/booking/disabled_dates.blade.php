@extends('adminlte::page')

@section('title', 'Nonaktifkan Tanggal - Kelola Booking')

@section('content_header')
    <h1>Nonaktifkan Tanggal Booking</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Tanggal Nonaktif</h3>
                </div>
                <form method="POST" action="{{ route('admin.bookings.store-disabled-date') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" name="tanggal" value="{{ old('tanggal') }}" 
                                   min="{{ date('Y-m-d') }}">
                            @error('tanggal')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan (Opsional)</label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror" 
                                   id="keterangan" name="keterangan" value="{{ old('keterangan') }}" 
                                   placeholder="Contoh: Libur Nasional, Maintenance, dll">
                            @error('keterangan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Tanggal Nonaktif</h3>
                </div>
                <div class="card-body">
                    @if($disabledDates->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($disabledDates as $date)
                                        <tr>
                                            <td>{{ $date->tanggal->format('d/m/Y') }}</td>
                                            <td>{{ $date->keterangan ?? '-' }}</td>
                                            <td>
                                                <form action="{{ route('admin.bookings.delete-disabled-date', $date->id) }}" 
                                                      method="POST" style="display: inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus tanggal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $disabledDates->links() }}
                    @else
                        <p class="text-muted">Belum ada tanggal yang dinonaktifkan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-header {
            background-color: #f8f9fa;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Disabled dates page loaded!');
        
        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif
        
        @if(session('error'))
            toastr.error('{{ session('error') }}');
        @endif
    </script>
@stop