@extends('layouts.app')

@section('title', 'Detail Orang Tua')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-person-vcard me-2"></i>
                    Detail Orang Tua
                </h5>
                <div>
                    <a href="{{ route('admin.ortus.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Informasi Akun</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Nama Lengkap</label>
                                    <div class="form-control bg-light">{{ $ortu->name }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Email</label>
                                    <div class="form-control bg-light">{{ $ortu->email }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Nomor Telepon</label>
                                    <div class="form-control bg-light">{{ $ortu->phone }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Role</label>
                                    <div class="form-control bg-light">
                                        <span class="badge bg-primary">{{ strtoupper($ortu->role) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Tanggal Dibuat</label>
                                    <div class="form-control bg-light">
                                        {{ \Carbon\Carbon::parse($ortu->created_at)->format('d F Y H:i') }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Terakhir Diupdate</label>
                                    <div class="form-control bg-light">
                                        {{ \Carbon\Carbon::parse($ortu->updated_at)->format('d F Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Siswa yang Dihubungkan</h6>
                            @if($ortu->students->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Jenis Kelamin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ortu->students as $student)
                                            <tr>
                                                <td>{{ $student->nis }}</td>
                                                <td>{{ $student->nama }}</td>
                                                <td>{{ $student->kelas }}</td>
                                                <td>{{ $student->jenis_kelamin }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Belum ada siswa yang dihubungkan dengan akun ini.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">Aksi</h6>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.ortus.edit', $ortu->id) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil me-1"></i> Edit Data
                                    </a>
                                    <form action="{{ route('admin.ortus.destroy', $ortu->id) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data orang tua ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.ortus.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-list-ul me-1"></i> Daftar Orang Tua
                                    </a>
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
