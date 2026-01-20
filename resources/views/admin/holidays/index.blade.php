@extends('layouts.app')

@section('title', 'Kelola Hari Libur')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-x me-2"></i>
                    Kelola Hari Libur
                </h5>
                <a href="{{ route('admin.holidays.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Hari Libur
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>Informasi:</strong> Hari libur akan dikeluarkan dari perhitungan hari sekolah aktif.
                            Sistem hanya menghitung hari Senin-Jumat yang bukan hari libur.
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Keterangan</th>
                                <th>Ditambahkan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($holidays as $holiday)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($holiday->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($holiday->tanggal)->locale('id')->dayName }}</td>
                                    <td>{{ $holiday->keterangan }}</td>
                                    <td>{{ $holiday->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.holidays.edit', $holiday->id) }}" 
                                               class="btn btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.holidays.destroy', $holiday->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Hapus hari libur ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Belum ada data hari libur</p>
                                            <a href="{{ route('admin.holidays.create') }}" class="btn btn-primary mt-2">
                                                <i class="bi bi-plus-circle me-1"></i> Tambah Hari Libur Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($holidays->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $holidays->links() }}
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
                    Informasi Hari Libur
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info mb-3">
                            <h6 class="alert-heading">Pengaruh Hari Libur</h6>
                            <ul class="mb-0">
                                <li>Hari libur tidak dihitung sebagai hari sekolah aktif</li>
                                <li>Persentase kehadiran dihitung: Hadir / Hari Sekolah Aktif</li>
                                <li>Hari sekolah aktif = Senin-Jumat (kecuali libur)</li>
                                <li>Data libur berlaku untuk semua kelas</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning mb-0">
                            <h6 class="alert-heading">Catatan Penting</h6>
                            <ul class="mb-0">
                                <li>Pastikan tanggal libur sudah benar</li>
                                <li>Hapus libur yang sudah tidak berlaku</li>
                                <li>Libur nasional biasanya berlaku setiap tahun</li>
                                <li>Libur sekolah bisa berbeda dengan libur nasional</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
