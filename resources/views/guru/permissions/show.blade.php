@extends('layouts.app')

@section('title', 'Detail Izin Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-check-circle me-2"></i>
                    Detail Izin Siswa
                </h5>
                <div class="time-display">
                    <i class="bi bi-clock"></i>
                    <span>{{ date('H:i') }}</span>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informasi Izin</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Siswa</label>
                                        <p class="mb-0">
                                            <strong>{{ $permission->student->nama }}</strong><br>
                                            <small class="text-muted">NIS: {{ $permission->student->nis }}</small>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Kelas</label>
                                        <p class="mb-0 fs-5">{{ $permission->student->kelas }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Tanggal Izin</label>
                                        <p class="mb-0">
                                            {{ \Carbon\Carbon::parse($permission->tanggal)->format('d/m/Y') }}<br>
                                            <small class="text-muted">
                                                Diajukan: {{ \Carbon\Carbon::parse($permission->created_at)->format('H:i') }}
                                            </small>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Status</label>
                                        <p class="mb-0">
                                            <span class="badge {{ $permission->status_badge_class }} fs-6">
                                                {{ $permission->status_text }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label text-muted">Alasan Izin</label>
                                        <div class="border rounded p-3 bg-light">
                                            {{ $permission->alasan }}
                                        </div>
                                    </div>
                                    @if($permission->keterangan)
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label text-muted">Keterangan Tambahan</label>
                                        <div class="border rounded p-3 bg-light">
                                            {{ $permission->keterangan }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Foto Bukti</h6>
                            </div>
                            <div class="card-body text-center">
                                @if($permission->foto_bukti)
                                    <div class="mb-3">
                                        <img src="{{ $permission->photo_url }}" 
                                             alt="Foto Bukti Izin" 
                                             class="img-fluid rounded"
                                             style="max-height: 300px; cursor: pointer;"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#photoModal">
                                        <p class="mt-2 text-muted">
                                            <small>Klik untuk memperbesar</small>
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        <p class="mt-2 text-muted">Tidak ada foto bukti</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Aksi Verifikasi</h6>
                            </div>
                            <div class="card-body">
                                @if($permission->status == 'Pending')
                                    <form action="{{ route('guru.permissions.approve', $permission->id) }}" method="POST" class="mb-3">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success w-100 mb-2" 
                                                onclick="return confirm('Setujui izin ini?')">
                                            <i class="bi bi-check-circle me-2"></i> Setujui Izin
                                        </button>
                                    </form>

                                    <form action="{{ route('guru.permissions.reject', $permission->id) }}" method="POST" class="mb-3">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger w-100 mb-2" 
                                                onclick="return confirm('Tolak izin ini?')">
                                            <i class="bi bi-x-circle me-2"></i> Tolak Izin
                                        </button>
                                    </form>

                                    <form action="{{ route('guru.permissions.update', $permission->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Ubah Status</label>
                                            <select name="status" id="status" class="form-select">
                                                <option value="Pending" {{ $permission->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Disetujui" {{ $permission->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                                <option value="Ditolak" {{ $permission->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="keterangan" class="form-label">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control" 
                                                      placeholder="Berikan keterangan jika perlu...">{{ old('keterangan', $permission->keterangan) }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-save me-2"></i> Simpan Perubahan
                                        </button>
                                    </form>
                                @else
                                    <div class="alert {{ $permission->status == 'Disetujui' ? 'alert-success' : 'alert-danger' }}">
                                        <i class="bi {{ $permission->status == 'Disetujui' ? 'bi-check-circle' : 'bi-x-circle' }} me-2"></i>
                                        Izin ini sudah 
                                        @if($permission->status == 'Disetujui')
                                            <strong>disetujui</strong>
                                        @else
                                            <strong>ditolak</strong>
                                        @endif
                                        pada {{ \Carbon\Carbon::parse($permission->updated_at)->format('d/m/Y H:i') }}
                                    </div>
                                @endif

                                <hr class="my-3">

                                <a href="{{ route('guru.permissions.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
@if($permission->foto_bukti)
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Bukti Izin - {{ $permission->student->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ $permission->photo_url }}" 
                     alt="Foto Bukti Izin" 
                     class="img-fluid rounded"
                     style="max-height: 70vh;">
                <div class="mt-3">
                    <p class="mb-1"><strong>Informasi:</strong></p>
                    <p class="mb-1 text-muted">Siswa: {{ $permission->student->nama }}</p>
                    <p class="mb-1 text-muted">Tanggal: {{ \Carbon\Carbon::parse($permission->tanggal)->format('d/m/Y') }}</p>
                    <p class="mb-0 text-muted">Alasan: {{ Str::limit($permission->alasan, 100) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mt-4">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Panduan Verifikasi Izin
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <h6 class="alert-heading">Langkah-langkah verifikasi:</h6>
                    <ol class="mb-2">
                        <li>Periksa kelengkapan data izin</li>
                        <li>Verifikasi keaslian foto bukti</li>
                        <li>Pastikan alasan izin sesuai dengan bukti</li>
                        <li>Setujui atau tolak izin berdasarkan validitas bukti</li>
                        <li>Berikan keterangan jika diperlukan</li>
                    </ol>
                    <hr>
                    <p class="mb-0"><strong>Catatan:</strong> Izin yang sudah diverifikasi tidak dapat diubah statusnya kecuali oleh admin.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
