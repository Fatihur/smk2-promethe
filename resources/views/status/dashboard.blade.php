@extends('adminlte::page')

@section('title', 'Status Dashboard')

@section('content_header')
    <h1>Status Dashboard - Proses Seleksi PPDB</h1>
@stop

@section('content')
@if($stats['tahun_akademik'])
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt"></i>
                        Tahun Akademik {{ $stats['tahun_akademik']->tahun }} - {{ $stats['tahun_akademik']->semester }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Progress Keseluruhan</h5>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: {{ $stats['process_status']['overall_progress']['percentage'] }}%">
                                    {{ $stats['process_status']['overall_progress']['percentage'] }}%
                                </div>
                            </div>
                            <p><strong>Status:</strong> {{ $stats['process_status']['overall_progress']['stage'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Statistik Siswa</h5>
                            <p><strong>Total Pendaftar:</strong> {{ $stats['siswa_stats']['total'] }}</p>
                            <p><strong>Sudah Diproses:</strong> {{ $stats['process_status']['overall_progress']['processed'] }}</p>
                            <p><strong>Belum Diproses:</strong> {{ $stats['process_status']['overall_progress']['total'] - $stats['process_status']['overall_progress']['processed'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['siswa_stats']['total'] }}</h3>
                    <p>Total Pendaftar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['siswa_stats']['khusus'] }}</h3>
                    <p>Kategori Khusus</p>
                </div>
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['siswa_stats']['umum'] }}</h3>
                    <p>Kategori Umum</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['siswa_stats']['lulus'] + $stats['siswa_stats']['lulus_pilihan_2'] }}</h3>
                    <p>Total Lulus</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Process Status -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Proses PROMETHEE</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="description-block border-right">
                                <span class="description-percentage {{ $stats['process_status']['khusus_promethee_done'] ? 'text-success' : 'text-muted' }}">
                                    <i class="fas {{ $stats['process_status']['khusus_promethee_done'] ? 'fa-check' : 'fa-clock' }}"></i>
                                </span>
                                <h5 class="description-header">{{ $stats['promethee_stats']['khusus_processed'] }}</h5>
                                <span class="description-text">Khusus Diproses</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="description-block">
                                <span class="description-percentage {{ $stats['process_status']['umum_promethee_done'] ? 'text-success' : 'text-muted' }}">
                                    <i class="fas {{ $stats['process_status']['umum_promethee_done'] ? 'fa-check' : 'fa-clock' }}"></i>
                                </span>
                                <h5 class="description-header">{{ $stats['promethee_stats']['umum_processed'] }}</h5>
                                <span class="description-text">Umum Diproses</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Progress Validasi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="description-block border-right">
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                         style="width: {{ $stats['process_status']['khusus_validation_progress'] }}%">
                                    </div>
                                </div>
                                <h5 class="description-header">{{ $stats['process_status']['khusus_validation_progress'] }}%</h5>
                                <span class="description-text">Validasi Khusus</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="description-block">
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $stats['process_status']['umum_validation_progress'] }}%">
                                    </div>
                                </div>
                                <h5 class="description-header">{{ $stats['process_status']['umum_validation_progress'] }}%</h5>
                                <span class="description-text">Validasi Umum</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Validation Statistics -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Hasil Validasi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="description-block border-right">
                                <h5 class="description-header text-warning">{{ $stats['validation_stats']['pending'] }}</h5>
                                <span class="description-text">Menunggu Validasi</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block border-right">
                                <h5 class="description-header text-success">{{ $stats['validation_stats']['lulus'] }}</h5>
                                <span class="description-text">Lulus</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block border-right">
                                <h5 class="description-header text-info">{{ $stats['validation_stats']['lulus_pilihan_2'] }}</h5>
                                <span class="description-text">Lulus Pilihan 2</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block">
                                <h5 class="description-header text-danger">{{ $stats['validation_stats']['tidak_lulus'] }}</h5>
                                <span class="description-text">Tidak Lulus</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jurusan Statistics -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Per Jurusan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Jurusan</th>
                                    <th>Pendaftar</th>
                                    <th>Diterima</th>
                                    <th>Kuota</th>
                                    <th>Sisa Kuota</th>
                                    <th>% Terisi</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['jurusan_stats'] as $jurusan)
                                    <tr>
                                        <td><span class="badge badge-primary">{{ $jurusan['jurusan']->kode_jurusan }}</span></td>
                                        <td>{{ $jurusan['jurusan']->nama_jurusan }}</td>
                                        <td>{{ $jurusan['pendaftar'] }}</td>
                                        <td>{{ $jurusan['diterima'] }}</td>
                                        <td>{{ $jurusan['kuota'] }}</td>
                                        <td>{{ $jurusan['sisa_kuota'] }}</td>
                                        <td>{{ $jurusan['persentase_terisi'] }}%</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar {{ $jurusan['persentase_terisi'] >= 100 ? 'bg-success' : ($jurusan['persentase_terisi'] >= 80 ? 'bg-warning' : 'bg-info') }}" 
                                                     role="progressbar" style="width: {{ min(100, $jurusan['persentase_terisi']) }}%">
                                                    {{ $jurusan['persentase_terisi'] }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        .description-block {
            text-align: center;
        }
        .progress {
            margin-bottom: 5px;
        }
    </style>
@stop

@section('js')
    <script>
        function refreshData() {
            location.reload();
        }

        // Auto refresh every 30 seconds
        setInterval(function() {
            refreshData();
        }, 30000);
    </script>
@stop
