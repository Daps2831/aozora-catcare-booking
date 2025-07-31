{{-- filepath: resources/views/admin/tim_groomer/edit.blade.php --}}
@extends('adminlte::page')
@section('title', 'Edit Tim Grooming')
@section('content_header')
    <h1>Edit Tim Grooming</h1>
@stop
@section('content')
<!-- <pre>{{ print_r($usedGroomerMap, true) }}</pre> -->
<form action="{{ route('admin.tim-groomer.update', $tim_groomer->id_tim) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nama Tim</label>
        <input type="text" name="nama_tim" class="form-control" value="{{ old('nama_tim', $tim_groomer->nama_tim) }}" required>
    </div>
    <div class="form-group">
        <label>Anggota 1</label>
        <select name="anggota_1" class="form-control" required>
            <option value="">Pilih Groomer</option>
            @foreach($groomers as $g)
                <option value="{{ $g->id_groomer }}"
                    {{ old('anggota_1', $tim_groomer->anggota_1) == $g->id_groomer ? 'selected' : '' }}>
                    {{ $g->nama }}{{ isset($usedGroomerMap[(string)$g->id_groomer]) ? ' (Sudah di Tim: '.$usedGroomerMap[(string)$g->id_groomer].')' : '' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Anggota 2 (Opsional)</label>
        <select name="anggota_2" class="form-control">
            <option value="">-- Tidak Ada --</option>
            @foreach($groomers as $g)
                <option value="{{ $g->id_groomer }}"
                    {{ old('anggota_2', $tim_groomer->anggota_2) == $g->id_groomer ? 'selected' : '' }}>
                    {{ $g->nama }}{{ isset($usedGroomerMap[(string)$g->id_groomer]) ? ' (Sudah di Tim: '.$usedGroomerMap[(string)$g->id_groomer].')' : '' }}
                </option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.tim-groomer.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const anggota1 = document.querySelector('select[name="anggota_1"]');
    const anggota2 = document.querySelector('select[name="anggota_2"]');
    const usedGroomerMap = @json($usedGroomerMap);

    function getGroomerName(id) {
        const opt = anggota1.querySelector('option[value="' + id + '"]') || anggota2.querySelector('option[value="' + id + '"]');
        return opt ? opt.text.replace(/\s*\(Sudah di Tim:.*\)/, '') : '';
    }

    function checkGroomerInOtherTeam(select) {
        const val = select.value;
        if (val && usedGroomerMap[String(val)]) {
            const namaGroomer = getGroomerName(val);
            const namaTim = usedGroomerMap[val];
            if (!confirm(`${namaGroomer} sudah ada di ${namaTim}. Apakah Anda ingin memindahkan ke tim ini?`)) {
                select.value = '';
                return false;
            }
        }
        return true;
    }

    anggota1.addEventListener('change', function() {
        checkGroomerInOtherTeam(anggota1);
    });
    anggota2.addEventListener('change', function() {
        checkGroomerInOtherTeam(anggota2);
    });

    // Validasi saat submit
    const form = anggota1.closest('form');
    form.addEventListener('submit', function(e) {
        if (anggota1.value && anggota2.value && anggota1.value === anggota2.value) {
            alert('Groomer tidak boleh sama di Anggota 1 dan Anggota 2!');
            e.preventDefault();
            return;
        }
        // Cek konfirmasi untuk anggota 1 dan 2
        let ok1 = checkGroomerInOtherTeam(anggota1);
        let ok2 = checkGroomerInOtherTeam(anggota2);
        if (!ok1 || !ok2) {
            e.preventDefault();
        }
    });
});
</script>
@endsection