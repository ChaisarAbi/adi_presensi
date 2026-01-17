@extends('layouts.app')

@section('title', 'Flagging Anak')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-flag me-2"></i>
                    Flagging Anak
                </h5>
                <div>
                    <a href="{{ route('ortu.flags.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Buat Flag Baru
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
                            <strong>Informasi:</strong> Anda sedang melihat flag untuk 
                            <strong>{{ $student->nama }}</strong> (Kelas: {{ $student->kelas }})
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Flag</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Ditangani Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($flags as $flag)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
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
                                        @if($flag->resolved_by)
                                            {{ $flag->resolvedBy->name ?? 'Sistem' }}
                                        @else
                                            <span class="text-muted">Belum ditangani</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('ortu.flags.show', $flag->id) }}" 
                                               class="btn btn-info">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if($flag->status == 'Aktif')
                                                <form action="{{ route('ortu.flags.destroy', $flag->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                            onclick="return confirm('Batalkan flag ini?')">
                                                        <i class="bi bi-trash"></i> Batalkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-flag" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Belum ada flag</p>
                                            <a href="{{ route('ortu.flags.create') }}" class="btn btn-primary mt-2">
                                                <i class="bi bi-plus-circle me-1"></i> Buat Flag Pertama
                                            </a>
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
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Panduan Flagging
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info mb-3">
                            <h6 class="alert-heading">Kapan Membuat Flag?</h6>
                            <ul class="mb-0">
                                <li>Anak belum pulang setelah jam pulang + toleransi</li>
                                <li>Ada kejadian darurat di sekolah</li>
                                <li>Anak mengalami masalah kesehatan di sekolah</li>
                                <li>Ada informasi penting yang perlu diketahui guru</li>
                                <li>Anak tidak hadir tanpa kabar</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning mb-0">
                            <h6 class="alert-heading">Proses Penanganan Flag</h6>
                            <ul class="mb-0">
                                <li>Flag akan diterima oleh guru/wali kelas</li>
                                <li>Guru akan menindaklanjuti sesuai kebutuhan</li>
                                <li>Status akan berubah menjadi "Selesai" setelah ditangani</li>
                                <li>Anda dapat membatalkan flag yang masih "Aktif"</li>
                                <li>Guru akan memberikan feedback melalui sistem</li>
                            </ul>
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
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Flag Otomatis Sistem
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <p class="mb-2"><strong>Sistem akan secara otomatis membuat flag ketika:</strong></p>
                    <ul class="mb-0">
                        <li>Anak belum pulang setelah jam pulang + toleransi (30 menit)</li>
                        <li>Anak tidak hadir tanpa izin selama 3 hari berturut-turut</li>
                        <li>Terdapat ketidaksesuaian data absensi</li>
                        <li>Anak terlambat lebih dari 3 kali dalam seminggu</li>
                    </ul>
                    <hr>
                    <p class="mb-0"><small>Flag otomatis akan ditangani oleh sistem dan guru secara bersamaan.</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
