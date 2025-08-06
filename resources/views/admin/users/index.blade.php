
{{-- filepath: c:\laragon\www\testing\resources\views\admin\users\index.blade.php --}}
@extends('adminlte::page')
@section('title', 'Kelola User')
@section('content_header')
    <h1>Kelola User</h1>
@stop

@section('content')
{{-- Alert Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar User</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th width="10%">Role</th>
                        <th width="10%">Status Customer</th>
                        <th width="15%">Tgl Registrasi</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge badge-info badge-sm ml-1">You</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            {{-- Tampilkan status customer untuk semua user selain admin --}}
                            @if($user->role !== 'admin')
                                @if($user->customer)
                                    @php
                                        $isComplete = $user->customer->name && 
                                                    $user->customer->username && 
                                                    $user->customer->email && 
                                                    $user->customer->kontak && 
                                                    $user->customer->alamat;
                                    @endphp
                                    
                                    @if($isComplete)
                                        <span class="badge badge-success">Profil Lengkap</span>
                                    @else
                                        <span class="badge badge-warning">Profil Belum Lengkap</span>
                                    @endif
                                @else
                                    <span class="badge badge-danger">Profil Kosong</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            {{-- Tombol Lihat - Selalu tersedia --}}
                            <a href="{{ route('admin.users.show', $user->id) }}" 
                               class="btn btn-info btn-sm" 
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            {{-- Tombol Edit - Hanya untuk non-admin --}}
                            @if($user->role !== 'admin')
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="btn btn-warning btn-sm" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" 
                                        disabled 
                                        title="Admin tidak dapat diedit">
                                    <i class="fas fa-lock"></i>
                                </button>
                            @endif
                            
                            {{-- Tombol Hapus - Hanya untuk non-admin dan bukan diri sendiri --}}
                            @if($user->role !== 'admin' && $user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                      method="POST" 
                                      style="display:inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}? Data ini tidak dapat dikembalikan.')">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" 
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm" 
                                        disabled 
                                        title="{{ $user->role === 'admin' ? 'Admin tidak dapat dihapus' : 'Tidak dapat menghapus diri sendiri' }}">
                                    <i class="fas fa-ban"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data user</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.badge-sm {
    font-size: 0.7em;
}
</style>
@stop

@section('js')
<script>
console.log('User index page loaded');
</script>
@stop