@extends('adminlte::page')

@section('title', 'Laporan & Cetak Hasil')

@section('content_header')
    <h1>Laporan & Cetak Hasil</h1>
@stop

@section('content')
@if($tahunAkademik)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt"></i>
                        Laporan Seleksi PPDB {{ $tahunAkademik->tahun }} - {{ $tahunAkademik->semester }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Statistik Keseluruhan</h5>
                            <ul class="list-unstyled">
                                <li><strong>Total Pendaftar:</strong> {{ $stats['siswa_stats']['total'] }} siswa</li>
                                <li><strong>Kategori Khusus:</strong> {{ $stats['siswa_stats']['khusus'] }} siswa</li>
                                <li><strong>Kategori Umum:</strong> {{ $stats['siswa_stats']['umum'] }} siswa</li>
                                <li><strong>Total Lulus:</strong> {{ $stats['siswa_stats']['lulus'] + $stats['siswa_stats']['lulus_pilihan_2'] }} siswa</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Status Proses</h5>
                            <ul class="list-unstyled">
                                <li><strong>Progress Keseluruhan:</strong> {{ $stats['process_status']['overall_progress']['percentage'] }}%</li>
                                <li><strong>Status:</strong> {{ $stats['process_status']['overall_progress']['stage'] }}</li>
                                <li><strong>PROMETHEE Khusus:</strong> {{ $stats['process_status']['khusus_promethee_done'] ? 'Selesai' : 'Belum' }}</li>
                                <li><strong>PROMETHEE Umum:</strong> {{ $stats['process_status']['umum_promethee_done'] ? 'Selesai' : 'Belum' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt text-primary"></i>
                        Hasil Seleksi
                    </h3>
                </div>
                <div class="card-body">
                    <p>Laporan lengkap hasil seleksi siswa dengan detail status dan penempatan jurusan.</p>
                    <div class="btn-group-vertical btn-block">
                        <a href="{{ route('panitia.reports.hasil-seleksi') }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Lihat Hasil Seleksi
                        </a>
                        <a href="{{ route('panitia.reports.print.hasil-seleksi') }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-print"></i> Cetak Hasil Seleksi
                        </a>
                        <a href="{{ route('panitia.reports.export.hasil-seleksi') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-excel"></i> Export ke Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-trophy text-warning"></i>
                        Ranking PROMETHEE
                    </h3>
                </div>
                <div class="card-body">
                    <p>Laporan ranking hasil perhitungan PROMETHEE untuk kategori Khusus dan Umum.</p>
                    <div class="btn-group-vertical btn-block">
                        <a href="{{ route('panitia.reports.ranking', ['kategori' => 'khusus']) }}" class="btn btn-warning">
                            <i class="fas fa-star"></i> Ranking Khusus
                        </a>
                        <a href="{{ route('panitia.reports.ranking', ['kategori' => 'umum']) }}" class="btn btn-info">
                            <i class="fas fa-users"></i> Ranking Umum
                        </a>
                        <a href="{{ route('panitia.reports.print.ranking', ['kategori' => 'khusus']) }}" class="btn btn-outline-warning" target="_blank">
                            <i class="fas fa-print"></i> Cetak Ranking Khusus
                        </a>
                        <a href="{{ route('panitia.reports.print.ranking', ['kategori' => 'umum']) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-print"></i> Cetak Ranking Umum
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-check-circle text-success"></i>
                        Daftar Lulus
                    </h3>
                </div>
                <div class="card-body">
                    <p>Daftar siswa yang dinyatakan lulus seleksi PPDB dan diterima di jurusan masing-masing.</p>
                    <div class="btn-group-vertical btn-block">
                        <a href="{{ route('panitia.reports.daftar-lulus') }}" class="btn btn-success">
                            <i class="fas fa-eye"></i> Lihat Daftar Lulus
                        </a>
                        <a href="{{ route('panitia.reports.print.daftar-lulus') }}" class="btn btn-outline-success" target="_blank">
                            <i class="fas fa-print"></i> Cetak Daftar Lulus
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar text-info"></i>
                        Statistik Per Jurusan
                    </h3>
                </div>
                <div class="card-body">
                    <p>Laporan statistik pendaftar, diterima, dan kuota untuk setiap jurusan.</p>
                    <div class="btn-group-vertical btn-block">
                        <a href="{{ route('panitia.reports.statistik') }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat Statistik
                        </a>
                        <a href="{{ route('panitia.reports.export.statistik') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-excel"></i> Export Statistik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt"></i>
                        Aksi Cepat
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('panitia.reports.print.hasil-seleksi') }}" class="btn btn-primary btn-block" target="_blank">
                                <i class="fas fa-print"></i><br>
                                Cetak Semua Hasil
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('panitia.reports.export.hasil-seleksi') }}" class="btn btn-success btn-block">
                                <i class="fas fa-download"></i><br>
                                Download Excel
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('panitia.reports.print.daftar-lulus') }}" class="btn btn-warning btn-block" target="_blank">
                                <i class="fas fa-certificate"></i><br>
                                Cetak Daftar Lulus
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('status.dashboard') }}" class="btn btn-info btn-block">
                                <i class="fas fa-tachometer-alt"></i><br>
                                Status Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Petunjuk Penggunaan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Laporan yang Tersedia:</h5>
                            <ul>
                                <li><strong>Hasil Seleksi:</strong> Data lengkap semua siswa dengan status seleksi</li>
                                <li><strong>Ranking PROMETHEE:</strong> Hasil perangkingan dengan skor Phi Net</li>
                                <li><strong>Daftar Lulus:</strong> Siswa yang diterima di jurusan masing-masing</li>
                                <li><strong>Statistik Jurusan:</strong> Ringkasan pendaftar dan kuota per jurusan</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Format Output:</h5>
                            <ul>
                                <li><strong>Lihat:</strong> Tampilan di browser dengan filter dan pencarian</li>
                                <li><strong>Cetak:</strong> Format PDF siap cetak dengan kop sekolah</li>
                                <li><strong>Excel:</strong> File spreadsheet untuk analisis lebih lanjut</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <h4><i class="icon fas fa-exclamation-triangle"></i> Tidak Ada Tahun Akademik Aktif</h4>
        <p>Belum ada tahun akademik yang aktif. Silakan hubungi administrator untuk mengaktifkan tahun akademik.</p>
    </div>
@endif
@stop

@section('css')
    <style>
        .btn-group-vertical .btn {
            margin-bottom: 5px;
        }
        .card-title i {
            margin-right: 8px;
        }
    </style>
@stop

@section('js')
    {{-- Add here extra scripts --}}
@stop
