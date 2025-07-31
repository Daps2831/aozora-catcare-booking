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
        <label>Nama Tim</label>
        <input type="text" name="nama_tim" class="form-control" value="{{ old('nama_tim') }}" required>
    </div>
    <div class="form-group">
        <label>Anggota 1</label>
        <select name="anggota_1" class="form-control" required>
            <option value="">Pilih Groomer</option>
            @foreach($groomers as $g)
                @if(!in_array($g->id_groomer, $usedGroomerIds))
                    <option value="{{ $g->id_groomer }}">{{ $g->nama }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Anggota 2 (Opsional)</label>
        <select name="anggota_2" class="form-control">
            <option value="">-- Tidak Ada --</option>
            @foreach($groomers as $g)
                @if(!in_array($g->id_groomer, $usedGroomerIds))
                    <option value="{{ $g->id_groomer }}" {{ old('anggota_2') == $g->id_groomer ? 'selected' : '' }}>
                        {{ $g->nama }}
                    </option>
                @endif
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.tim-groomer.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const anggota1 = document.querySelector('select[name="anggota_1"]');
    const anggota2 = document.querySelector('select[name="anggota_2"]');

    function filterAnggota2() {
        const selected1 = anggota1.value;
        anggota2.querySelectorAll('option').forEach(opt => {
            opt.hidden = (opt.value && opt.value === selected1);
        });
        // Jika anggota2 sudah sama dengan anggota1, reset
        if (anggota2.value === selected1) {
            anggota2.value = '';
        }
    }

    anggota1.addEventListener('change', filterAnggota2);
    filterAnggota2();

    // Tambahkan validasi sebelum submit
    const form = anggota1.closest('form');
    form.addEventListener('submit', function(e) {
        if (anggota1.value && anggota2.value && anggota1.value === anggota2.value) {
            alert('Groomer tidak boleh sama di Anggota 1 dan Anggota 2!');
            e.preventDefault();
        }
    });
});
</script>
@endsection