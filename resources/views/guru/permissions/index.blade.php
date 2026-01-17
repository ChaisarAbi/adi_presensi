@extends('layouts.app')

@section('title', 'Verifikasi Izin Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-check-circle me-2"></i>
                    Verifikasi Izin Siswa
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

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Tanggal Izin</th>
                                <th>Alasan</th>
                                <th>Foto Bukti</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $permission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $permission->student->nama }}</strong><br>
                                        <small class="text-muted">NIS: {{ $permission->student->nis }}</small>
                                    </td>
                                    <td>{{ $permission->student->kelas }}</td>
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
                                        <span class="badge {{ $permission->status_badge_class }}">
                                            {{ $permission->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('guru.permissions.show', $permission->id) }}" 
                                               class="btn btn-info">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if($permission->status == 'Pending')
                                                <form action="{{ route('guru.permissions.approve', $permission->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success" 
                                                            onclick="return confirm('Setujui izin ini?')">
                                                        <i class="bi bi-check"></i> Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('guru.permissions.reject', $permission->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger" 
                                                            onclick="return confirm('Tolak izin ini?')">
                                                        <i class="bi bi-x"></i> Tolak
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
                                                <img src="{{ $permission->photo_url }}" 
                                                     alt="Foto Bukti" 
                                                     class="img-fluid rounded"
                                                     style="max-height: 500px;">
                                                <p class="mt-3 text-muted">
                                                    <small>Bukti izin dari {{ $permission->student->nama }}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Tidak ada izin yang perlu diverifikasi</p>
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
    <div class="col-md-3 col-6">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h1 class="display-4 text-primary">{{ $stats['total'] }}</h1>
                <p class="text-muted mb-0">Total Izin</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h1 class="display-4 text-warning">{{ $stats['pending'] }}</h1>
                <p class="text-muted mb-0">Menunggu</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h1 class="display-4 text-success">{{ $stats['approved'] }}</h1>
                <p class="text-muted mb-0">Disetujui</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h1 class="display-4 text-danger">{{ $stats['rejected'] }}</h1>
                <p class="text-muted mb-0">Ditolak</p>
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
                    Panduan Verifikasi Izin
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <h6 class="alert-heading">Proses Verifikasi Izin</h6>
                    <p class="mb-2">Sebagai guru, Anda dapat:</p>
                    <ul class="mb-0">
                        <li>Melihat detail izin siswa</li>
                        <li>Memeriksa foto bukti yang diupload</li>
                        <li>Menyetujui atau menolak izin yang masih pending</li>
                        <li>Hanya izin dengan status "Pending" yang dapat diverifikasi</li>
                        <li>Pastikan foto bukti valid sebelum menyetujui</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
