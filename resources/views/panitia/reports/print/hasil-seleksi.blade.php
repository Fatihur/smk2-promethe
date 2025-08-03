<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Seleksi PPDB {{ $tahunAkademik->tahun }}</title>
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
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
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
        <h2>HASIL SELEKSI PENERIMAAN PESERTA DIDIK BARU (PPDB)</h2>
        <p>Tahun Pelajaran {{ $tahunAkademik->tahun }}</p>
        <p>Jl. Garuda No. 1 Sumbawa Besar - NTB</p>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td width="150"><strong>Kategori</strong></td>
                <td width="10">:</td>
                <td>{{ $kategori == 'all' ? 'Semua Kategori' : ucfirst($kategori) }}</td>
                <td width="150"><strong>Tanggal Cetak</strong></td>
                <td width="10">:</td>
                <td>{{ date('d/m/Y H:i') }} WIB</td>
            </tr>
            <tr>
                <td><strong>Jurusan</strong></td>
                <td>:</td>
                <td>{{ $jurusanName }}</td>
                <td><strong>Total Siswa</strong></td>
                <td>:</td>
                <td>{{ $siswa->count() }} siswa</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">No. Pendaftaran</th>
                <th width="12%">NISN</th>
                <th width="20%">Nama Lengkap</th>
                <th width="5%">L/P</th>
                <th width="15%">Pilihan 1</th>
                <th width="15%">Pilihan 2</th>
                <th width="8%">Kategori</th>
                <th width="8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa as $index => $s)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center"><strong>{{ $s->no_pendaftaran }}</strong></td>
                    <td class="text-center">{{ $s->nisn }}</td>
                    <td>{{ $s->nama_lengkap }}</td>
                    <td class="text-center">{{ $s->jenis_kelamin }}</td>
                    <td>{{ $s->pilihanJurusan1->kode_jurusan }} - {{ $s->pilihanJurusan1->nama_jurusan }}</td>
                    <td>{{ $s->pilihanJurusan2 ? $s->pilihanJurusan2->kode_jurusan . ' - ' . $s->pilihanJurusan2->nama_jurusan : '-' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $s->kategori == 'khusus' ? 'badge-warning' : 'badge-info' }}">
                            {{ ucfirst($s->kategori) }}
                        </span>
                    </td>
                    <td class="text-center">
                        @switch($s->status_seleksi)
                            @case('pending')
                                <span class="badge badge-secondary">Pending</span>
                                @break
                            @case('lulus')
                                <span class="badge badge-success">Lulus</span>
                                @break
                            @case('lulus_pilihan_2')
                                <span class="badge badge-info">Lulus P2</span>
                                @break
                            @case('tidak_lulus')
                                <span class="badge badge-danger">Tidak Lulus</span>
                                @break
                        @endswitch
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data siswa</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table style="width: 100%; margin-top: 30px;">
            <tr>
                <td width="50%">
                    <strong>Rekapitulasi:</strong><br>
                    Total Siswa: {{ $siswa->count() }}<br>
                    Lulus: {{ $siswa->where('status_seleksi', 'lulus')->count() }}<br>
                    Lulus Pilihan 2: {{ $siswa->where('status_seleksi', 'lulus_pilihan_2')->count() }}<br>
                    Tidak Lulus: {{ $siswa->where('status_seleksi', 'tidak_lulus')->count() }}<br>
                    Pending: {{ $siswa->where('status_seleksi', 'pending')->count() }}
                </td>
                <td width="50%" style="text-align: right;">
                    <div class="signature">
                        Sumbawa Besar, {{ date('d F Y') }}<br>
                        Panitia PPDB<br><br><br><br>
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
