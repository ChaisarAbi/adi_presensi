@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Tambah Data Siswa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.students.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis" value="{{ old('nis') }}" required>
                                @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="class_schedule_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select class="form-select @error('class_schedule_id') is-invalid @enderror" id="class_schedule_id" name="class_schedule_id" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_schedule_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->kelas }} ({{ $class->jam_masuk_formatted }} - {{ $class->jam_pulang_formatted }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_schedule_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ortu_id" class="form-label">Orang Tua</label>
                                <select class="form-select @error('ortu_id') is-invalid @enderror" id="ortu_id" name="ortu_id">
                                    <option value="">Pilih Orang Tua (Opsional)</option>
                                    @foreach($ortu as $parent)
                                        <option value="{{ $parent->id }}" {{ old('ortu_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }} ({{ $parent->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('ortu_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Barcode akan digenerate otomatis setelah data disimpan.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
