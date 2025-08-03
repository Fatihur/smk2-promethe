<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa Lulus PPDB {{ $tahunAkademik->tahun }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section table {
            width: 100%;
        }
        .info-section td {
            padding: 3px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-primary { background-color: #007bff; color: white; }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMK NEGERI 2 SUMBAWA BESAR</h1>
        <h2>DAFTAR SISWA LULUS PENERIMAAN PESERTA DIDIK BARU (PPDB)</h2>
        <p>Tahun Pelajaran {{ $tahunAkademik->tahun }}</p>
        <p>Jl. Garuda No. 1 Sumbawa Besar - NTB</p>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td width="150"><strong>Jurusan</strong></td>
                <td width="10">:</td>
                <td>{{ $jurusanName }}</td>
                <td width="150"><strong>Tanggal Cetak</strong></td>
                <td width="10">:</td>
                <td>{{ date('d/m/Y H:i') }} WIB</td>
            </tr>
            <tr>
                <td><strong>Total Lulus</strong></td>
                <td>:</td>
                <td>{{ $siswaLulus->count() }} siswa</td>
                <td><strong>Lulus Pilihan 1</strong></td>
                <td>:</td>
                <td>{{ $siswaLulus->where('status_seleksi', 'lulus')->count() }} siswa</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">No. Pendaftaran</th>
                <th width="12%">NISN</th>
                <th width="25%">Nama Lengkap</th>
                <th width="5%">L/P</th>
                <th width="20%">Asal Sekolah</th>
                <th width="11%">Status Lulus</th>
                <th width="10%">Jurusan Diterima</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswaLulus as $index => $s)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center"><strong>{{ $s->no_pendaftaran }}</strong></td>
                    <td class="text-center">{{ $s->nisn }}</td>
                    <td>{{ $s->nama_lengkap }}</td>
                    <td class="text-center">{{ $s->jenis_kelamin }}</td>
                    <td>{{ $s->asal_sekolah }}</td>
                    <td class="text-center">
                        @if($s->status_seleksi == 'lulus')
                            <span class="badge badge-success">Pilihan 1</span>
                        @else
                            <span class="badge badge-info">Pilihan 2</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-primary">{{ $s->jurusanDiterima->kode_jurusan }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada siswa lulus</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table style="width: 100%; margin-top: 30px;">
            <tr>
                <td width="50%">
                    <strong>Rekapitulasi Lulus:</strong><br>
                    Total Lulus: {{ $siswaLulus->count() }} siswa<br>
                    Lulus Pilihan 1: {{ $siswaLulus->where('status_seleksi', 'lulus')->count() }} siswa<br>
                    Lulus Pilihan 2: {{ $siswaLulus->where('status_seleksi', 'lulus_pilihan_2')->count() }} siswa<br><br>
                    
                    <strong>Catatan:</strong><br>
                    Siswa yang tercantum dalam daftar ini dinyatakan<br>
                    LULUS dan diterima di SMK Negeri 2 Sumbawa Besar<br>
                    sesuai dengan jurusan yang tertera.
                </td>
                <td width="50%" style="text-align: right;">
                    <div class="signature">
                        Sumbawa Besar, {{ date('d F Y') }}<br>
                        Kepala Sekolah<br><br><br><br>
                        <strong>_________________________</strong><br>
                        NIP. 
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
