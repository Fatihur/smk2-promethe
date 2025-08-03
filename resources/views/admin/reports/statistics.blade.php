@extends('adminlte::page')

@section('title', 'Laporan Statistik')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Laporan Statistik</h1>
        <div>
            <span class="badge badge-info">{{ $tahunAkademik->nama_tahun }}</span>
        </div>
    </div>
@stop

@section('content')
<!-- Overall Statistics -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalSiswa }}</h3>
                <p>Total Pendaftar</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalDiterima }}</h3>
                <p>Total Diterima</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $statsKategori['khusus']['pendaftar'] }}</h3>
                <p>Kategori Khusus</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $statsKategori['umum']['pendaftar'] }}</h3>
                <p>Kategori Umum</p>
            </div>
            <div class="icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
    </div>
</div>

<!-- Category Statistics -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Kategori Khusus</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-blue">
                                <i class="fas fa-users"></i>
                            </span>
                            <h5 class="description-header">{{ $statsKategori['khusus']['pendaftar'] }}</h5>
                            <span class="description-text">PENDAFTAR</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-green">
                                <i class="fas fa-check"></i>
                            </span>
                            <h5 class="description-header">{{ $statsKategori['khusus']['diterima'] }}</h5>
                            <span class="description-text">DITERIMA</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="description-block">
                            <span class="description-percentage text-yellow">
                                <i class="fas fa-percentage"></i>
                            </span>
                            <h5 class="description-header">
                                {{ $statsKategori['khusus']['pendaftar'] > 0 ? round(($statsKategori['khusus']['diterima'] / $statsKategori['khusus']['pendaftar']) * 100, 1) : 0 }}%
                            </h5>
                            <span class="description-text">TINGKAT PENERIMAAN</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Kategori Umum</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-blue">
                                <i class="fas fa-users"></i>
                            </span>
                            <h5 class="description-header">{{ $statsKategori['umum']['pendaftar'] }}</h5>
                            <span class="description-text">PENDAFTAR</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-green">
                                <i class="fas fa-check"></i>
                            </span>
                            <h5 class="description-header">{{ $statsKategori['umum']['diterima'] }}</h5>
                            <span class="description-text">DITERIMA</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="description-block">
                            <span class="description-percentage text-blue">
                                <i class="fas fa-percentage"></i>
                            </span>
                            <h5 class="description-header">
                                {{ $statsKategori['umum']['pendaftar'] > 0 ? round(($statsKategori['umum']['diterima'] / $statsKategori['umum']['pendaftar']) * 100, 1) : 0 }}%
                            </h5>
                            <span class="description-text">TINGKAT PENERIMAAN</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jurusan Statistics -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Per Jurusan</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Jurusan</th>
                            <th>Kategori</th>
                            <th>Pendaftar</th>
                            <th>Diproses</th>
                            <th>Diterima</th>
                            <th>Tidak Diterima</th>
                            <th>Kuota</th>
                            <th>Sisa Kuota</th>
                            <th>% Terisi</th>
                            <th>Rata-rata Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statsJurusan as $stat)
                            <tr>
                                <td>
                                    <strong>{{ $stat['jurusan']->nama_jurusan }}</strong>
                                    <br><small class="text-muted">{{ $stat['jurusan']->kode_jurusan }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $stat['jurusan']->kategori == 'khusus' ? 'warning' : 'primary' }}">
                                        {{ ucfirst($stat['jurusan']->kategori) }}
                                    </span>
                                </td>
                                <td>{{ $stat['total_pendaftar'] }}</td>
                                <td>{{ $stat['total_diproses'] }}</td>
                                <td>
                                    <span class="badge badge-success">{{ $stat['diterima'] }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-danger">{{ $stat['tidak_diterima'] }}</span>
                                </td>
                                <td>{{ $stat['kuota'] }}</td>
                                <td>
                                    <span class="text-{{ $stat['sisa_kuota'] > 0 ? 'success' : 'danger' }}">
                                        {{ $stat['sisa_kuota'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-{{ $stat['persentase_terisi'] >= 100 ? 'success' : ($stat['persentase_terisi'] >= 75 ? 'warning' : 'info') }}" 
                                             style="width: {{ min($stat['persentase_terisi'], 100) }}%">
                                        </div>
                                    </div>
                                    <small>{{ $stat['persentase_terisi'] }}%</small>
                                </td>
                                <td>{{ number_format($stat['rata_rata_skor'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Belum ada data jurusan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Gender Statistics -->
@if(!empty($genderStats))
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Berdasarkan Jenis Kelamin</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="description-block">
                            <span class="description-percentage text-blue">
                                <i class="fas fa-male"></i>
                            </span>
                            <h5 class="description-header">{{ $genderStats['L'] ?? 0 }}</h5>
                            <span class="description-text">LAKI-LAKI</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <span class="description-percentage text-pink">
                                <i class="fas fa-female"></i>
                            </span>
                            <h5 class="description-header">{{ $genderStats['P'] ?? 0 }}</h5>
                            <span class="description-text">PEREMPUAN</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tren Pendaftaran Bulanan</h3>
            </div>
            <div class="card-body">
                @if($monthlyTrend->count() > 0)
                    <div class="chart">
                        <canvas id="monthlyChart" style="height: 200px;"></canvas>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data tren pendaftaran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@stop

@section('css')
<style>
.description-block {
    text-align: center;
}
.description-header {
    margin: 0;
    padding: 0;
    font-weight: 600;
    font-size: 16px;
}
.description-text {
    text-transform: uppercase;
    font-weight: 400;
    font-size: 11px;
}
</style>
@stop

@section('js')
@if($monthlyTrend->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    var ctx = document.getElementById('monthlyChart').getContext('2d');
    var monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
            datasets: [{
                label: 'Pendaftar',
                data: {!! json_encode($monthlyTrend->pluck('total')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endif
@stop
