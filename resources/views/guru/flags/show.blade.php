@extends('layouts.app')

@section('title', 'Detail Flagging Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-flag me-2"></i>
                    Detail Flagging Siswa
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
                                <h6 class="mb-0">Informasi Flag</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Siswa</label>
                                        <p class="mb-0">
                                            <strong>{{ $flag->student->nama }}</strong><br>
                                            <small class="text-muted">NIS: {{ $flag->student->nis }}</small>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Kelas</label>
                                        <p class="mb-0 fs-5">{{ $flag->student->kelas }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Tanggal Flag</label>
                                        <p class="mb-0">
                                            {{ \Carbon\Carbon::parse($flag->tanggal)->format('d/m/Y') }}<br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($flag->waktu_flag)->format('H:i') ?? 'Tidak tercatat' }}
                                            </small>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Status</label>
                                        <p class="mb-0">
                                            @if($flag->status == 'Aktif')
                                                <span class="badge bg-danger fs-6">Aktif</span>
                                            @else
                                                <span class="badge bg-success fs-6">Selesai</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Ditandai Oleh</label>
                                        <p class="mb-0">
                                            @if($flag->flagged_by)
                                                {{ $flag->flaggedBy->name ?? 'Sistem' }}<br>
                                                <small class="text-muted">Orang Tua</small>
                                            @else
                                                Sistem
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Waktu Scan Pulang</label>
                                        <p class="mb-0">
                                            @if($flag->waktu_scan_pulang)
                                                {{ \Carbon\Carbon::parse($flag->waktu_scan_pulang)->format('H:i') }}
                                            @else
                                                <span class="text-muted">Tidak tercatat</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Keterangan</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $flag->keterangan ?? 'Tidak ada keterangan' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Aksi</h6>
                            </div>
                            <div class="card-body">
                                @if($flag->status == 'Aktif')
                                    <form action="{{ route('guru.flags.resolve', $flag->id) }}" method="POST" class="mb-3">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success w-100 mb-2" 
                                                onclick="return confirm('Tandai flag sebagai selesai?')">
                                            <i class="bi bi-check-circle me-2"></i> Tandai Selesai
                                        </button>
                                    </form>

                                    <form action="{{ route('guru.flags.update', $flag->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Ubah Status</label>
                                            <select name="status" id="status" class="form-select">
                                                <option value="Aktif" {{ $flag->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="Selesai" {{ $flag->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control" 
                                                      placeholder="Tambahkan keterangan jika perlu...">{{ old('keterangan', $flag->keterangan) }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-save me-2"></i> Simpan Perubahan
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-success">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Flag ini sudah diselesaikan pada 
                                        {{ \Carbon\Carbon::parse($flag->updated_at)->format('d/m/Y H:i') }}
                                    </div>
                                @endif

                                <hr class="my-3">

                                <a href="{{ route('guru.flags.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
                                </a>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informasi Kontak</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    <i class="bi bi-person me-2"></i>
                                    <strong>Orang Tua:</strong> 
                                    @if($flag->student->ortu)
                                        {{ $flag->student->ortu->name ?? 'Tidak tercatat' }}
                                    @else
                                        Tidak tercatat
                                    @endif
                                </p>
                                <p class="mb-0">
                                    <i class="bi bi-telephone me-2"></i>
                                    <strong>Telepon:</strong> 
                                    @if($flag->student->ortu && $flag->student->ortu->phone)
                                        {{ $flag->student->ortu->phone }}
                                    @else
                                        Tidak tercatat
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Panduan Penanganan Flag
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <h6 class="alert-heading">Langkah-langkah penanganan flag:</h6>
                    <ol class="mb-2">
                        <li>Verifikasi informasi flag dengan siswa/orang tua</li>
                        <li>Catat tindakan yang telah dilakukan</li>
                        <li>Update status flag setelah ditangani</li>
                        <li>Berikan keterangan tambahan jika diperlukan</li>
                    </ol>
                    <hr>
                    <p class="mb-0"><strong>Catatan:</strong> Flag yang sudah diselesaikan akan tetap tersimpan dalam sistem untuk keperluan monitoring dan pelaporan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
