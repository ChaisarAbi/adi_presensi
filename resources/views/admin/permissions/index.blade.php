@extends('layouts.app')

@section('title', 'Manajemen Izin Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-envelope-paper me-2"></i>
                    Manajemen Izin Siswa
                </h5>
                <div>
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Izin
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

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Foto Bukti</th>
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
                                    <td>{{ \Carbon\Carbon::parse($permission->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="text-truncate-2" style="max-width: 200px;">
                                            {{ $permission->alasan }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($permission->status == 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($permission->status == 'Disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($permission->foto_bukti)
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#fotoModal{{ $permission->id }}">
                                                <i class="bi bi-image"></i> Lihat
                                            </button>
                                            
                                            <!-- Modal -->
                                            <div class="modal fade" id="fotoModal{{ $permission->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Foto Bukti Izin</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ asset('storage/' . $permission->foto_bukti) }}" 
                                                                 class="img-fluid rounded" 
                                                                 alt="Foto Bukti Izin"
                                                                 style="max-height: 500px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if($permission->status == 'Pending')
                                                <form action="{{ route('admin.permissions.approve', $permission->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.permissions.reject', $permission->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.permissions.destroy', $permission->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" 
                                                        onclick="return confirm('Hapus izin ini?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Belum ada data izin</p>
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
    <div class="col-md-4">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h1 class="display-4 text-primary">{{ $stats['total'] }}</h1>
                <p class="text-muted mb-0">Total Izin</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h1 class="display-4 text-success">{{ $stats['approved'] }}</h1>
                <p class="text-muted mb-0">Disetujui</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h1 class="display-4 text-warning">{{ $stats['pending'] }}</h1>
                <p class="text-muted mb-0">Pending</p>
            </div>
        </div>
    </div>
</div>
@endsection
