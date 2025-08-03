@extends('adminlte::page')

@section('title', 'Statistik Seleksi')

@section('content_header')
    <h1>Statistik Seleksi</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-bar"></i>
            Statistik Seleksi PPDB {{ $tahunAkademik->tahun }} - {{ $tahunAkademik->semester }}
        </h3>
        <div class="card-tools">
            <a href="{{ route('panitia.reports.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('panitia.reports.export.statistik') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Overall Statistics -->
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
                        <h3 class="card-title">Status Proses</h3>
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
                        
                        <div class="mt-3">
                            <h6>Progress Keseluruhan</h6>
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: {{ $stats['process_status']['overall_progress']['percentage'] }}%">
                                    {{ $stats['process_status']['overall_progress']['percentage'] }}%
                                </div>
                            </div>
                            <small class="text-muted">{{ $stats['process_status']['overall_progress']['stage'] }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Statistik Validasi</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header text-warning">{{ $stats['validation_stats']['pending'] }}</h5>
                                    <span class="description-text">Menunggu Validasi</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="description-block">
                                    <h5 class="description-header text-success">{{ $stats['validation_stats']['lulus'] + $stats['validation_stats']['lulus_pilihan_2'] }}</h5>
                                    <span class="description-text">Sudah Divalidasi</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header text-info">{{ $stats['validation_stats']['lulus_pilihan_2'] }}</h5>
                                    <span class="description-text">Lulus Pilihan 2</span>
                                </div>
                            </div>
                            <div class="col-6">
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
                            <table class="table table-bordered table-striped" id="statistikTable">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Jurusan</th>
                                        <th>Kuota</th>
                                        <th>Pendaftar</th>
                                        <th>Diterima</th>
                                        <th>Sisa Kuota</th>
                                        <th>% Terisi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['jurusan_stats'] as $jurusan)
                                        <tr>
                                            <td><span class="badge badge-primary">{{ $jurusan['jurusan']->kode_jurusan }}</span></td>
                                            <td>{{ $jurusan['jurusan']->nama_jurusan }}</td>
                                            <td class="text-center">{{ $jurusan['kuota'] }}</td>
                                            <td class="text-center">{{ $jurusan['pendaftar'] }}</td>
                                            <td class="text-center"><strong>{{ $jurusan['diterima'] }}</strong></td>
                                            <td class="text-center">{{ $jurusan['sisa_kuota'] }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar {{ $jurusan['persentase_terisi'] >= 100 ? 'bg-success' : ($jurusan['persentase_terisi'] >= 80 ? 'bg-warning' : 'bg-info') }}" 
                                                         role="progressbar" style="width: {{ min(100, $jurusan['persentase_terisi']) }}%">
                                                        {{ $jurusan['persentase_terisi'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($jurusan['persentase_terisi'] >= 100)
                                                    <span class="badge badge-success">Penuh</span>
                                                @elseif($jurusan['persentase_terisi'] >= 80)
                                                    <span class="badge badge-warning">Hampir Penuh</span>
                                                @else
                                                    <span class="badge badge-info">Tersedia</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <th colspan="2">TOTAL</th>
                                        <th class="text-center">{{ collect($stats['jurusan_stats'])->sum('kuota') }}</th>
                                        <th class="text-center">{{ collect($stats['jurusan_stats'])->sum('pendaftar') }}</th>
                                        <th class="text-center">{{ collect($stats['jurusan_stats'])->sum('diterima') }}</th>
                                        <th class="text-center">{{ collect($stats['jurusan_stats'])->sum('sisa_kuota') }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Distribusi Kategori</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="kategoriChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status Seleksi</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
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
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#statistikTable').DataTable({
                "paging": false,
                "searching": true,
                "ordering": true,
                "info": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });

            // Kategori Chart
            const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
            new Chart(kategoriCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Kategori Khusus', 'Kategori Umum'],
                    datasets: [{
                        data: [{{ $stats['siswa_stats']['khusus'] }}, {{ $stats['siswa_stats']['umum'] }}],
                        backgroundColor: ['#ffc107', '#17a2b8']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: ['Pending', 'Lulus', 'Lulus P2', 'Tidak Lulus'],
                    datasets: [{
                        data: [
                            {{ $stats['siswa_stats']['pending'] }}, 
                            {{ $stats['siswa_stats']['lulus'] }}, 
                            {{ $stats['siswa_stats']['lulus_pilihan_2'] }}, 
                            {{ $stats['siswa_stats']['tidak_lulus'] }}
                        ],
                        backgroundColor: ['#6c757d', '#28a745', '#17a2b8', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@stop
