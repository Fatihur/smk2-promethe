@extends('adminlte::page')

@section('title', 'Overview Kriteria Jurusan')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Overview Kriteria Jurusan</h1>
        <div class="btn-group">
            <a href="{{ route('admin.kriteria-overview.export', 'json') }}" class="btn btn-info btn-sm">
                <i class="fas fa-download"></i> Export JSON
            </a>
            <a href="{{ route('admin.kriteria-overview.export', 'csv') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
        </div>
    </div>
@stop

@section('content')
<!-- Summary Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-graduation-cap"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Jurusan</span>
                <span class="info-box-number">{{ $jurusans->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-list-ol"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Master Kriteria</span>
                <span class="info-box-number">{{ $masterKriterias->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Konfigurasi</span>
                <span class="info-box-number">{{ collect($matrix)->sum(function($item) { return count($item['kriteria']); }) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-cogs"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rata-rata per Jurusan</span>
                <span class="info-box-number">{{ $jurusans->count() > 0 ? round(collect($matrix)->sum(function($item) { return count($item['kriteria']); }) / $jurusans->count(), 1) : 0 }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Kriteria Matrix Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Matrix Kriteria per Jurusan</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th rowspan="2" class="align-middle">Jurusan</th>
                        <th colspan="{{ $masterKriterias->count() }}" class="text-center">Kriteria (Rentang Nilai)</th>
                    </tr>
                    <tr>
                        @foreach($masterKriterias as $kriteria)
                            <th class="text-center">
                                <div>{{ $kriteria->kode_kriteria }}</div>
                                <small class="text-muted">{{ $kriteria->nama_kriteria }}</small>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($matrix as $jurusanId => $data)
                        <tr>
                            <td class="font-weight-bold">
                                <div>{{ $data['jurusan']->kode_jurusan }}</div>
                                <small class="text-muted">{{ $data['jurusan']->nama_jurusan }}</small>
                            </td>
                            @foreach($masterKriterias as $kriteria)
                                <td class="text-center">
                                    @if(isset($data['kriteria'][$kriteria->id]))
                                        @php
                                            $kj = $data['kriteria'][$kriteria->id];
                                            $isRange = $kj->nilai_min != $kj->nilai_max;
                                            $badgeClass = $kj->is_active ? 'badge-primary' : 'badge-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            @if($isRange)
                                                {{ $kj->nilai_min }} - {{ $kj->nilai_max }}
                                            @else
                                                {{ $kj->nilai_min }}
                                            @endif
                                        </span>
                                        @if(!$kj->is_active)
                                            <br><small class="text-muted">(Tidak Aktif)</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $masterKriterias->count() + 1 }}" class="text-center">
                                Belum ada data kriteria yang dikonfigurasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detailed View per Jurusan -->
<div class="row">
    @foreach($matrix as $jurusanId => $data)
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <strong>{{ $data['jurusan']->kode_jurusan }}</strong>
                        <br><small class="text-muted">{{ $data['jurusan']->nama_jurusan }}</small>
                    </h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.kriteria-jurusan.index', $data['jurusan']) }}" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Kelola
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        @forelse($data['kriteria'] as $kriteriaId => $kj)
                            <tr>
                                <td>
                                    <strong>{{ $kj->masterKriteria->kode_kriteria }}</strong>
                                    <br><small class="text-muted">{{ $kj->masterKriteria->nama_kriteria }}</small>
                                </td>
                                <td class="text-right">
                                    @php
                                        $isRange = $kj->nilai_min != $kj->nilai_max;
                                        $badgeClass = $kj->is_active ? 'badge-primary' : 'badge-secondary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        @if($isRange)
                                            {{ $kj->nilai_min }} - {{ $kj->nilai_max }}
                                        @else
                                            {{ $kj->nilai_min }}
                                        @endif
                                    </span>
                                    @if(!$kj->is_active)
                                        <br><small class="text-muted">Tidak Aktif</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    Belum ada kriteria
                                </td>
                            </tr>
                        @endforelse
                    </table>
                </div>
                @if(count($data['kriteria']) > 0)
                    <div class="card-footer text-center">
                        <small class="text-muted">
                            {{ count($data['kriteria']) }} kriteria dikonfigurasi
                        </small>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<!-- Legend -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Keterangan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Status Badge:</h6>
                <ul class="list-unstyled">
                    <li><span class="badge badge-primary">Aktif</span> - Kriteria aktif dan digunakan</li>
                    <li><span class="badge badge-secondary">Tidak Aktif</span> - Kriteria tidak aktif</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Format Nilai:</h6>
                <ul class="list-unstyled">
                    <li><code>91 - 100</code> - Rentang nilai (dari 91 sampai 100)</li>
                    <li><code>5</code> - Nilai tetap (hanya nilai 5)</li>
                    <li><code>-</code> - Kriteria belum dikonfigurasi</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.table th {
    vertical-align: middle;
}
.card-title {
    line-height: 1.2;
}
.badge {
    font-size: 0.85em;
}
.table-responsive {
    overflow-x: auto;
}
@media (max-width: 768px) {
    .table-responsive table {
        font-size: 0.85em;
    }
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-refresh every 5 minutes
    setTimeout(function() {
        location.reload();
    }, 300000);
});
</script>
@stop
