@extends('adminlte::page')

@section('title', 'Laporan Ranking PROMETHEE')

@section('content_header')
    <h1>Laporan Ranking PROMETHEE</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-trophy"></i>
            Ranking PROMETHEE Kategori {{ ucfirst($kategori) }} - {{ $tahunAkademik->tahun }}
        </h3>
        <div class="card-tools">
            <a href="{{ route('panitia.reports.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('panitia.reports.print.ranking', ['kategori' => $kategori]) }}" class="btn btn-primary btn-sm" target="_blank">
                <i class="fas fa-print"></i> Cetak
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Category Switcher -->
        <div class="mb-3">
            <div class="btn-group" role="group">
                <a href="{{ route('panitia.reports.ranking', ['kategori' => 'khusus']) }}" 
                   class="btn {{ $kategori == 'khusus' ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="fas fa-star"></i> Kategori Khusus
                </a>
                <a href="{{ route('panitia.reports.ranking', ['kategori' => 'umum']) }}" 
                   class="btn {{ $kategori == 'umum' ? 'btn-info' : 'btn-outline-info' }}">
                    <i class="fas fa-users"></i> Kategori Umum
                </a>
            </div>
        </div>

        <!-- Summary -->
        <div class="alert alert-info">
            <div class="row">
                <div class="col-md-3">
                    <strong>Total Siswa:</strong> {{ $results->count() }}
                </div>
                <div class="col-md-3">
                    <strong>Dalam Kuota:</strong> {{ $results->where('masuk_kuota', true)->count() }}
                </div>
                <div class="col-md-3">
                    <strong>Luar Kuota:</strong> {{ $results->where('masuk_kuota', false)->count() }}
                </div>
                <div class="col-md-3">
                    <strong>Kategori:</strong> {{ ucfirst($kategori) }}
                </div>
            </div>
        </div>

        @if($results->isNotEmpty())
            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="rankingTable">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>No. Pendaftaran</th>
                            <th>Nama Siswa</th>
                            <th>Pilihan Jurusan</th>
                            <th>Phi+</th>
                            <th>Phi-</th>
                            <th>Phi Net</th>
                            <th>Status Kuota</th>
                            <th>Status Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $result)
                            <tr class="{{ $result->masuk_kuota ? 'table-success' : 'table-light' }}">
                                <td>
                                    <span class="badge {{ $result->masuk_kuota ? 'badge-success' : 'badge-secondary' }} badge-lg">
                                        #{{ $result->ranking }}
                                    </span>
                                </td>
                                <td><strong>{{ $result->siswa->no_pendaftaran }}</strong></td>
                                <td>{{ $result->siswa->nama_lengkap }}</td>
                                <td>
                                    <strong>Pilihan 1:</strong> 
                                    <span class="badge badge-primary">{{ $result->siswa->pilihanJurusan1->kode_jurusan }}</span>
                                    {{ $result->siswa->pilihanJurusan1->nama_jurusan }}
                                    @if($result->siswa->pilihanJurusan2)
                                        <br><strong>Pilihan 2:</strong> 
                                        <span class="badge badge-secondary">{{ $result->siswa->pilihanJurusan2->kode_jurusan }}</span>
                                        {{ $result->siswa->pilihanJurusan2->nama_jurusan }}
                                    @endif
                                </td>
                                <td>{{ number_format($result->phi_plus, 4) }}</td>
                                <td>{{ number_format($result->phi_minus, 4) }}</td>
                                <td>
                                    <strong class="{{ $result->phi_net >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($result->phi_net, 4) }}
                                    </strong>
                                </td>
                                <td>
                                    @if($result->masuk_kuota)
                                        <span class="badge badge-success">Dalam Kuota</span>
                                    @else
                                        <span class="badge badge-secondary">Luar Kuota</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($result->status_validasi)
                                        @case('pending')
                                            <span class="badge badge-warning">Menunggu Validasi</span>
                                            @break
                                        @case('lulus')
                                            <span class="badge badge-success">Lulus</span>
                                            @break
                                        @case('lulus_pilihan_2')
                                            <span class="badge badge-info">Lulus Pilihan 2</span>
                                            @break
                                        @case('tidak_lulus')
                                            <span class="badge badge-danger">Tidak Lulus</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">-</span>
                                    @endswitch
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Statistics -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Statistik Phi Net Score</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $phiNetScores = $results->pluck('phi_net');
                                $maxScore = $phiNetScores->max();
                                $minScore = $phiNetScores->min();
                                $avgScore = $phiNetScores->avg();
                            @endphp
                            <div class="row">
                                <div class="col-4">
                                    <div class="description-block border-right">
                                        <h5 class="description-header text-success">{{ number_format($maxScore, 4) }}</h5>
                                        <span class="description-text">Skor Tertinggi</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="description-block border-right">
                                        <h5 class="description-header text-info">{{ number_format($avgScore, 4) }}</h5>
                                        <span class="description-text">Skor Rata-rata</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="description-block">
                                        <h5 class="description-header text-danger">{{ number_format($minScore, 4) }}</h5>
                                        <span class="description-text">Skor Terendah</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Distribusi Status</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $statusCounts = $results->groupBy('status_validasi')->map->count();
                            @endphp
                            <div class="row">
                                <div class="col-6">
                                    <div class="description-block border-right">
                                        <h5 class="description-header text-warning">{{ $statusCounts['pending'] ?? 0 }}</h5>
                                        <span class="description-text">Pending</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="description-block">
                                        <h5 class="description-header text-success">{{ ($statusCounts['lulus'] ?? 0) + ($statusCounts['lulus_pilihan_2'] ?? 0) }}</h5>
                                        <span class="description-text">Lulus</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Belum Ada Data</h5>
                <p>Belum ada hasil ranking PROMETHEE untuk kategori {{ $kategori }}.</p>
                <a href="{{ route('panitia.promethee.index') }}" class="btn btn-warning">
                    <i class="fas fa-play"></i> Jalankan PROMETHEE
                </a>
            </div>
        @endif
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <style>
        .table-success {
            background-color: rgba(40, 167, 69, 0.1) !important;
        }
        .badge-lg {
            font-size: 0.9em;
            padding: 0.5em 0.75em;
        }
        .description-block {
            text-align: center;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#rankingTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 25,
                "order": [[ 0, "asc" ]], // Sort by ranking
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
@stop
