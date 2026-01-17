@extends('layouts.app')

@section('title', 'Detail Guru')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge me-2"></i>
                    Detail Guru
                </h5>
                <div>
                    <a href="{{ route('admin.gurus.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-dashboard mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Informasi Guru
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Nama Lengkap</label>
                                        <div class="form-control bg-light">{{ $guru->name }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Email</label>
                                        <div class="form-control bg-light">{{ $guru->email }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">NIP</label>
                                        <div class="form-control bg-light">{{ $guru->nip ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Nomor Telepon</label>
                                        <div class="form-control bg-light">{{ $guru->phone ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Role</label>
                                        <div class="form-control bg-light">
                                            <span class="badge bg-success">{{ strtoupper($guru->role) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Tanggal Dibuat</label>
                                        <div class="form-control bg-light">
                                            {{ \Carbon\Carbon::parse($guru->created_at)->format('d F Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card card-dashboard mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-person-circle me-2"></i>
                                    Foto Profil
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="bi bi-person-badge" style="font-size: 5rem; color: #667eea;"></i>
                                </div>
                                <h5 class="mb-1">{{ $guru->name }}</h5>
                                <p class="text-muted mb-3">Guru</p>
                            </div>
                        </div>

                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-gear me-2"></i>
                                    Aksi
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.gurus.edit', $guru->id) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil me-1"></i> Edit Data
                                    </a>
                                    <form action="{{ route('admin.gurus.destroy', $guru->id) }}" method="POST" class="d-grid">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="bi bi-trash me-1"></i> Hapus Data
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-clock-history me-2"></i>
                                    Statistik
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card card-dashboard">
                                            <div class="card-body text-center">
                                                <div class="display-4 text-primary">0</div>
                                                <p class="text-muted mb-0">Absensi Di-scan</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card card-dashboard">
                                            <div class="card-body text-center">
                                                <div class="display-4 text-success">0</div>
                                                <p class="text-muted mb-0">Izin Diverifikasi</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card card-dashboard">
                                            <div class="card-body text-center">
                                                <div class="display-4 text-info">0</div>
                                                <p class="text-muted mb-0">Flag Ditangani</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
