@extends('layouts.app')

@section('title', 'Pengajuan Izin Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-envelope-paper me-2"></i>
                    Pengajuan Izin Siswa
                </h5>
                <div>
                    <a href="{{ route('ortu.permissions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Ajukan Izin Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>Informasi:</strong> Anda sedang melihat izin untuk 
                            <strong>{{ $student->nama }}</strong> (Kelas: {{ $student->kelas }})
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Izin</th>
                                <th>Alasan</th>
                                <th>Foto Bukti</th>
                                <th>Status</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $permission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($permission->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="text-truncate-2" style="max-width: 200px;">
                                            {{ $permission->alasan }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($permission->foto_bukti)
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#photoModal{{ $permission->id }}">
                                                <i class="bi bi-eye"></i> Lihat
                                            </button>
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($permission->status == 'Pending')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($permission->status == 'Disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($permission->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('ortu.permissions.show', $permission->id) }}" 
                                               class="btn btn-info">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if($permission->status == 'Pending')
                                                <form action="{{ route('ortu.permissions.destroy', $permission->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                            onclick="return confirm('Batalkan pengajuan izin ini?')">
                                                        <i class="bi bi-trash"></i> Batalkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Photo Modal -->
                                @if($permission->foto_bukti)
                                <div class="modal fade" id="photoModal{{ $permission->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Foto Bukti Izin</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ Storage::url($permission->foto_bukti) }}" 
                                                     alt="Foto Bukti" 
                                                     class="img-fluid rounded"
                                                     style="max-height: 500px;">
                                                <p class="mt-3 text-muted">
                                                    <small>Bukti izin untuk {{ $student->nama }}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-envelope-paper" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Belum ada pengajuan izin</p>
                                            <a href="{{ route('ortu.permissions.create') }}" class="btn btn-primary mt-2">
                                                <i class="bi bi-plus-circle me-1"></i> Ajukan Izin Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($permissions->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $permissions->links() }}
                    </div>
                @endif
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
                    Panduan Pengajuan Izin
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info mb-3">
                            <h6 class="alert-heading">Syarat Pengajuan Izin</h6>
                            <ul class="mb-0">
                                <li>Foto bukti wajib diupload (maksimal 2MB)</li>
                                <li>Format foto: JPG, PNG, GIF</li>
                                <li>Alasan izin harus jelas dan lengkap</li>
                                <li>Tanggal izin tidak boleh lebih dari 30 hari ke depan</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning mb-0">
                            <h6 class="alert-heading">Proses Verifikasi</h6>
                            <ul class="mb-0">
                                <li>Izin akan diverifikasi oleh guru/wali kelas</li>
                                <li>Proses verifikasi maksimal 2x24 jam</li>
                                <li>Anda dapat membatalkan izin yang masih "Menunggu"</li>
                                <li>Status akan berubah menjadi "Disetujui" atau "Ditolak"</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
