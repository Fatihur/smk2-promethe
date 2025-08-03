@extends('adminlte::page')

@section('title', 'Monitor Proses Seleksi')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Monitor Proses Seleksi</h1>
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
                <h3>{{ $siswaKhusus }}</h3>
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
                <h3>{{ $siswaUmum }}</h3>
                <p>Kategori Umum</p>
            </div>
            <div class="icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Monitor Proses Seleksi</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{ route('admin.selection-process.khusus') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-star"></i> Khusus
                        </a>
                        <a href="{{ route('admin.selection-process.umum') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-users"></i> Umum
                        </a>
                        <a href="{{ route('admin.selection-process.monitor') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Monitor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress by Category -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Progress Kategori Khusus</h3>
            </div>
            <div class="card-body">
                <div class="progress mb-3">
                    <div class="progress-bar bg-warning" style="width: {{ $progressKhusus }}%">
                        {{ $progressKhusus }}%
                    </div>
                </div>
                <p class="text-muted">
                    {{ $siswaKhusus > 0 ? round(($progressKhusus / 100) * $siswaKhusus) : 0 }} dari {{ $siswaKhusus }} siswa telah diproses
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Progress Kategori Umum</h3>
            </div>
            <div class="card-body">
                <div class="progress mb-3">
                    <div class="progress-bar bg-primary" style="width: {{ $progressUmum }}%">
                        {{ $progressUmum }}%
                    </div>
                </div>
                <p class="text-muted">
                    {{ $siswaUmum > 0 ? round(($progressUmum / 100) * $siswaUmum) : 0 }} dari {{ $siswaUmum }} siswa telah diproses
                </p>
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
                            <th>Diterima</th>
                            <th>Kuota</th>
                            <th>Sisa Kuota</th>
                            <th>Progress</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jurusanStats as $stat)
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
                                <td>
                                    <span class="badge badge-success">{{ $stat['diterima'] }}</span>
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
                                    <small class="text-muted">{{ $stat['persentase_terisi'] }}%</small>
                                </td>
                                <td>
                                    @if($stat['sisa_kuota'] <= 0)
                                        <span class="badge badge-success">PENUH</span>
                                    @elseif($stat['persentase_terisi'] >= 75)
                                        <span class="badge badge-warning">HAMPIR PENUH</span>
                                    @else
                                        <span class="badge badge-info">TERSEDIA</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data jurusan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aktivitas Terbaru</h3>
            </div>
            <div class="card-body">
                @if($recentActivities->count() > 0)
                    <div class="timeline">
                        @foreach($recentActivities as $activity)
                            <div class="time-label">
                                <span class="bg-{{ $activity->status_kelulusan == 'diterima' ? 'success' : 'danger' }}">
                                    {{ $activity->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                            <div>
                                <i class="fas fa-{{ $activity->masuk_kuota ? 'check' : 'times' }} bg-{{ $activity->masuk_kuota ? 'success' : 'danger' }}"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">
                                        <strong>{{ $activity->siswa->nama_lengkap }}</strong>
                                        {{ $activity->masuk_kuota ? 'masuk kuota' : 'tidak masuk kuota' }}
                                        @if($activity->siswa->jurusanDiterima)
                                            di <strong>{{ $activity->siswa->jurusanDiterima->nama_jurusan }}</strong>
                                        @endif
                                    </h3>
                                    <div class="timeline-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">No. Pendaftaran:</small> {{ $activity->siswa->no_pendaftaran }}<br>
                                                <small class="text-muted">Kategori:</small>
                                                <span class="badge badge-{{ $activity->kategori == 'khusus' ? 'warning' : 'primary' }}">
                                                    {{ ucfirst($activity->kategori) }}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Phi Net:</small> {{ number_format($activity->phi_net, 4) }}<br>
                                                <small class="text-muted">Ranking:</small> #{{ $activity->ranking }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada aktivitas seleksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.timeline {
    position: relative;
    margin: 0 0 30px 0;
    padding: 0;
    list-style: none;
}
.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #ddd;
    left: 31px;
    margin: 0;
    border-radius: 2px;
}
</style>
@stop
