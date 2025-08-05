
{{-- filepath: resources/views/admin/kucing/create.blade.php --}}
@extends('adminlte::page')
@section('title', 'Tambah Kucing')
@section('content_header')
    <h1>Tambah Kucing untuk {{ $user->name }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-cat"></i> Form Tambah Kucing
        </h3>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.kucing.store', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Upload Foto Kucing --}}
            <div class="form-group">
                <label for="gambar">Foto Kucing</label>
                <div class="upload-area" id="upload-area">
                    <div class="upload-content">
                        <i class="fas fa-camera"></i>
                        <p>Klik atau drag & drop foto kucing di sini</p>
                        <small>Format: JPG, PNG, JPEG (Max: 2MB)</small>
                    </div>
                    <input type="file" id="gambar-input" name="gambar" accept="image/jpeg,image/png,image/jpg" style="display: none;">
                </div>
                
                {{-- Preview Area --}}
                <div class="image-preview-container" id="preview-container" style="display: none;">
                    <img id="image-preview" src="#" alt="Preview Foto Kucing">
                    <div class="preview-actions">
                        <button type="button" id="btn-change-photo" class="btn btn-secondary btn-sm">
                            <i class="fas fa-edit"></i> Ganti Foto
                        </button>
                        <button type="button" id="btn-remove-photo" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
                
                @error('gambar')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Nama Kucing -->
                    <div class="form-group">
                        <label for="nama_kucing">Nama Kucing <span class="text-danger">*</span></label>
                        <input type="text" 
                               id="nama_kucing" 
                               name="nama_kucing" 
                               class="form-control @error('nama_kucing') is-invalid @enderror"
                               value="{{ old('nama_kucing') }}" 
                               required>
                        @error('nama_kucing')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <!-- Jenis Kucing - Sekarang bisa diketik -->
                    <div class="form-group">
                        <label for="jenis">Jenis Kucing <span class="text-danger">*</span></label>
                        <input type="text" 
                               id="jenis" 
                               name="jenis" 
                               class="form-control @error('jenis') is-invalid @enderror"
                               value="{{ old('jenis') }}" 
                               placeholder="Masukkan jenis kucing (contoh: Persia, Anggora, Domestik, dll)"
                               required>
                        @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contoh: Persia, Anggora, Maine Coon, British Shorthair, Scottish Fold, Ragdoll, Domestik, Campuran, dll</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Umur Kucing -->
                    <div class="form-group">
                        <label for="umur">Umur Kucing <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" 
                                   id="umur" 
                                   name="umur" 
                                   class="form-control @error('umur') is-invalid @enderror"
                                   value="{{ old('umur') }}" 
                                   min="0" 
                                   max="30" 
                                   required>
                            <div class="input-group-append">
                                <span class="input-group-text">tahun</span>
                            </div>
                        </div>
                        @error('umur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Customer</label>
                        <div class="form-control-plaintext">
                            <strong>{{ $user->name }}</strong>
                            <br><small class="text-muted">{{ $user->email }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Kesehatan -->
            <div class="form-group">
                <label for="riwayat_kesehatan">Riwayat Kesehatan</label>
                <textarea id="riwayat_kesehatan" 
                          name="riwayat_kesehatan" 
                          class="form-control @error('riwayat_kesehatan') is-invalid @enderror"
                          rows="4"
                          placeholder="Masukkan riwayat kesehatan kucing (vaksin, penyakit, operasi, dll)">{{ old('riwayat_kesehatan') }}</textarea>
                @error('riwayat_kesehatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Kucing
                </button>
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
<style>
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.upload-area:hover {
    border-color: #007bff;
    background: #e3f2fd;
}

.upload-area.dragover {
    border-color: #0056b3;
    background: #bbdefb;
    transform: scale(1.02);
}

.upload-content i {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.upload-content p {
    font-size: 1.1rem;
    color: #495057;
    margin-bottom: 0.5rem;
    margin: 0;
}

.upload-content small {
    color: #6c757d;
}

.image-preview-container {
    text-align: center;
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: #fff;
    margin-bottom: 1rem;
}

.image-preview-container img {
    max-width: 300px;
    max-height: 300px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 1rem;
}

.preview-actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.text-danger {
    color: #dc3545 !important;
}
</style>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('gambar-input');
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const btnChangePhoto = document.getElementById('btn-change-photo');
    const btnRemovePhoto = document.getElementById('btn-remove-photo');

    // Click to upload
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });
    
    btnChangePhoto.addEventListener('click', function() {
        fileInput.click();
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    // Remove photo
    btnRemovePhoto.addEventListener('click', function() {
        fileInput.value = '';
        uploadArea.style.display = 'block';
        previewContainer.style.display = 'none';
    });

    function handleFileSelect(file) {
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Salah',
                    text: 'Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.'
                });
            } else {
                alert('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
            }
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file terlalu besar. Maksimal 2MB.'
                });
            } else {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
            }
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            uploadArea.style.display = 'none';
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@stop