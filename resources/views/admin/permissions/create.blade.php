@extends('layouts.app')

@section('title', 'Tambah Izin Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Tambah Izin Siswa
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.permissions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                                <select class="form-select @error('student_id') is-invalid @enderror" 
                                        id="student_id" name="student_id" required>
                                    <option value="">Pilih Siswa</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" 
                                                {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->nama }} ({{ $student->nis }}) - {{ $student->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Izin <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                       id="tanggal" name="tanggal" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan Izin <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alasan') is-invalid @enderror" 
                                  id="alasan" name="alasan" rows="4" 
                                  placeholder="Masukkan alasan izin..." required>{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="foto_bukti" class="form-label">Foto Bukti <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('foto_bukti') is-invalid @enderror" 
                                       id="foto_bukti" name="foto_bukti" accept="image/*" required>
                                <small class="text-muted">Upload foto bukti izin (max: 2MB, format: jpg, jpeg, png)</small>
                                @error('foto_bukti')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Disetujui" {{ old('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="Ditolak" {{ old('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="3" 
                                  placeholder="Masukkan keterangan tambahan jika ada...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Izin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview Image Script -->
<script>
    document.getElementById('foto_bukti').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Remove existing preview
                const existingPreview = document.getElementById('imagePreview');
                if (existingPreview) {
                    existingPreview.remove();
                }
                
                // Create preview container
                const previewContainer = document.createElement('div');
                previewContainer.id = 'imagePreview';
                previewContainer.className = 'mt-3';
                
                // Create image element
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-fluid rounded border';
                img.style.maxHeight = '200px';
                
                // Create remove button
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-danger mt-2';
                removeBtn.innerHTML = '<i class="bi bi-trash"></i> Hapus Preview';
                removeBtn.onclick = function() {
                    previewContainer.remove();
                    document.getElementById('foto_bukti').value = '';
                };
                
                // Append elements
                previewContainer.appendChild(img);
                previewContainer.appendChild(document.createElement('br'));
                previewContainer.appendChild(removeBtn);
                
                // Insert after file input
                const fileInput = document.getElementById('foto_bukti');
                fileInput.parentNode.appendChild(previewContainer);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
