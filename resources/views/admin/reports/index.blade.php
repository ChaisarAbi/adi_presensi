@extends('layouts.app')

@section('title', 'Generate Laporan Absensi')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Generate Laporan Absensi PDF</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.generate') }}" method="POST" id="reportForm">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bulan" class="form-label">Bulan</label>
                                <select class="form-select" id="bulan" name="bulan" required>
                                    <option value="">Pilih Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('bulan', date('n')) == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select class="form-select" id="tahun" name="tahun" required>
                                    <option value="">Pilih Tahun</option>
                                    @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                        <option value="{{ $i }}" {{ old('tahun', date('Y')) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <select class="form-select" id="kelas" name="kelas" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelasList as $kelasItem)
                                        <option value="{{ $kelasItem->kelas }}" {{ old('kelas') == $kelasItem->kelas ? 'selected' : '' }}>
                                            Kelas {{ $kelasItem->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Wali Kelas</label>
                                <div class="form-control" id="waliKelasInfo">
                                    <span class="text-muted">Pilih kelas terlebih dahulu</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Laporan akan menampilkan data absensi siswa per bulan sesuai kelas yang dipilih.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-download me-1"></i> Generate & Download PDF
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="previewBtn">
                            <i class="bi bi-eye me-1"></i> Preview
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="card card-dashboard mt-4 d-none" id="previewSection">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-eye me-2"></i>Preview Laporan</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status" id="previewSpinner">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div id="previewContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kelasSelect = document.getElementById('kelas');
    const waliKelasInfo = document.getElementById('waliKelasInfo');
    const previewBtn = document.getElementById('previewBtn');
    const previewSection = document.getElementById('previewSection');
    const previewContent = document.getElementById('previewContent');
    const previewSpinner = document.getElementById('previewSpinner');
    const reportForm = document.getElementById('reportForm');
    
    // Data wali kelas dari PHP (dikonversi ke JavaScript)
    const waliKelasData = @json($waliKelasList->pluck('nama', 'kelas'));
    
    // Update wali kelas info ketika kelas dipilih
    kelasSelect.addEventListener('change', function() {
        const selectedKelas = this.value;
        if (selectedKelas && waliKelasData[selectedKelas]) {
            waliKelasInfo.innerHTML = `<strong>${waliKelasData[selectedKelas]}</strong>`;
        } else {
            waliKelasInfo.innerHTML = '<span class="text-muted">Wali kelas tidak ditemukan</span>';
        }
    });
    
    // Trigger change event untuk inisialisasi
    if (kelasSelect.value) {
        kelasSelect.dispatchEvent(new Event('change'));
    }
    
    // Preview button handler
    previewBtn.addEventListener('click', function() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        const kelas = document.getElementById('kelas').value;
        
        if (!bulan || !tahun || !kelas) {
            alert('Harap lengkapi semua field terlebih dahulu!');
            return;
        }
        
        // Show preview section
        previewSection.classList.remove('d-none');
        previewContent.innerHTML = '';
        previewSpinner.classList.remove('d-none');
        
        // AJAX request untuk preview
        fetch('{{ route("admin.reports.preview") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                bulan: bulan,
                tahun: tahun,
                kelas: kelas
            })
        })
        .then(response => response.text())
        .then(html => {
            previewSpinner.classList.add('d-none');
            previewContent.innerHTML = html;
            
            // Scroll to preview section
            previewSection.scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            previewSpinner.classList.add('d-none');
            previewContent.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Gagal memuat preview: ${error.message}
                </div>
            `;
        });
    });
    
    // Form submission handler
    reportForm.addEventListener('submit', function(e) {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        const kelas = document.getElementById('kelas').value;
        
        if (!bulan || !tahun || !kelas) {
            e.preventDefault();
            alert('Harap lengkapi semua field terlebih dahulu!');
            return;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Generating PDF...';
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
});
</script>
@endpush
