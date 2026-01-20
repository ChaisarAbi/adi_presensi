<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Kelas {{ $kelas }} - {{ $monthName }} {{ $tahun }}</title>
    <style>
        @page {
            margin: 20px;
            size: A4 landscape;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #2c3e50;
        }
        .header h2 {
            font-size: 14px;
            margin: 5px 0;
            color: #34495e;
        }
        .header h3 {
            font-size: 12px;
            margin: 5px 0;
            color: #7f8c8d;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            width: 120px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }
        .report-table th {
            background-color: #2c3e50;
            color: white;
            padding: 8px 5px;
            text-align: center;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        .report-table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .report-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .report-table tr:hover {
            background-color: #f1f1f1;
        }
        .no {
            width: 30px;
        }
        .nis {
            width: 100px;
        }
        .nama {
            width: 180px;
            text-align: left;
        }
        .jk {
            width: 40px;
        }
        .rombel {
            width: 80px;
        }
        .count {
            width: 60px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #7f8c8d;
        }
        .signature {
            margin-top: 30px;
            width: 100%;
        }
        .signature td {
            text-align: center;
            padding-top: 40px;
        }
        .signature .line {
            border-top: 1px solid #333;
            width: 200px;
            margin: 0 auto;
            padding-top: 5px;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ABSENSI SISWA</h1>
        <h2>KELAS {{ $kelas }} - {{ $monthName }} {{ $tahun }}</h2>
        <h3>SISTEM PRESENSI SISWA BERBASIS WEB</h3>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Wali Kelas:</td>
            <td>{{ $waliKelas->nama ?? 'Tidak Diketahui' }}</td>
            <td class="label">Jumlah Siswa:</td>
            <td>{{ count($reportData) }} Siswa</td>
        </tr>
        <tr>
            <td class="label">Bulan:</td>
            <td>{{ $monthName }} {{ $tahun }}</td>
            <td class="label">Dibuat Tanggal:</td>
            <td>{{ $generatedDate }}</td>
        </tr>
    </table>

    @if(count($reportData) > 0)
        <table class="report-table">
            <thead>
                <tr>
                    <th class="no">No</th>
                    <th class="nis">NIS</th>
                    <th class="nama">Nama Siswa</th>
                    <th class="jk">JK</th>
                    <th class="rombel">Rombel</th>
                <th class="count">Hadir</th>
                <th class="count">Izin</th>
                <th class="count">Tidak Hadir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data['student']->nis }}</td>
                    <td class="text-left">{{ $data['student']->nama }}</td>
                    <td>{{ $data['student']->jenis_kelamin }}</td>
                    <td>{{ $data['student']->rombel }}</td>
                    <td>{{ $data['hadir'] }}</td>
                    <td>{{ $data['izin'] }}</td>
                    <td>{{ $data['tidak_hadir'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #7f8c8d;">
            <h3>Tidak ada data absensi untuk periode ini</h3>
            <p>Belum ada data kehadiran siswa untuk kelas {{ $kelas }} pada {{ $monthName }} {{ $tahun }}</p>
        </div>
    @endif

    <div class="footer">
        <table class="signature">
            <tr>
                <td style="width: 50%;">
                    <div class="line"></div>
                    <div>Wali Kelas</div>
                </td>
                <td style="width: 50%;">
                    <div class="line"></div>
                    <div>Kepala Sekolah</div>
                </td>
            </tr>
        </table>
        
        <div style="margin-top: 20px; font-size: 9px; color: #95a5a6;">
            <p>Dokumen ini dihasilkan secara otomatis oleh Sistem Presensi Siswa Berbasis Web</p>
            <p>© {{ date('Y') }} - Semua hak dilindungi undang-undang</p>
        </div>
    </div>
</body>
</html>
