@extends('layouts.app')

@section('title', 'Manajemen Flagging Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-flag me-2"></i>
                    Manajemen Flagging Siswa
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
                                <th>Tanggal Flag</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Ditandai Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($flags as $flag)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $flag->student->nama }}</strong><br>
                                        <small class="text-muted">NIS: {{ $flag->student->nis }}</small>
                                    </td>
                                    <td>{{ $flag->student->kelas }}</td>
                                    <td>{{ \Carbon\Carbon::parse($flag->tanggal)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="text-truncate-2" style="max-width: 200px;">
                                            {{ $flag->keterangan }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($flag->status == 'Aktif')
                                            <span class="badge bg-danger">Aktif</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($flag->flagged_by)
                                            {{ $flag->flaggedBy->name ?? 'Sistem' }}
                                        @else
                                            Sistem
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('guru.flags.show', $flag->id) }}" 
                                               class="btn btn-info">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if($flag->status == 'Aktif')
                                                <form action="{{ route('guru.flags.resolve', $flag->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success" 
                                                            onclick="return confirm('Tandai flag sebagai selesai?')">
                                                        <i class="bi bi-check-circle"></i> Selesai
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-flag" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Tidak ada flag yang aktif</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($flags->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $flags->links() }}
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
                <p class="text-muted mb-0">Total Flag</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h1 class="display-4 text-danger">{{ $stats['active'] }}</h1>
                <p class="text-muted mb-0">Flag Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h1 class="display-4 text-success">{{ $stats['resolved'] }}</h1>
                <p class="text-muted mb-0">Flag Selesai</p>
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
                    Informasi Flagging
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <h6 class="alert-heading">Sistem Flagging Otomatis</h6>
                    <p class="mb-2">Sistem akan secara otomatis membuat flag ketika:</p>
                    <ul class="mb-0">
                        <li>Siswa belum pulang setelah jam pulang + toleransi</li>
                        <li>Siswa tidak hadir tanpa izin selama 3 hari berturut-turut</li>
                        <li>Terdapat ketidaksesuaian data absensi</li>
                    </ul>
                    <hr>
                    <p class="mb-0"><strong>Sebagai guru, Anda dapat:</strong></p>
                    <ul class="mb-0">
                        <li>Melihat detail flag yang aktif</li>
                        <li>Menandai flag sebagai selesai setelah ditangani</li>
                        <li>Memantau siswa yang memerlukan perhatian khusus</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
