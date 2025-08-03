@extends('adminlte::page')

@section('title', 'Hasil PROMETHEE Umum')

@section('content_header')
    <h1>Hasil PROMETHEE Kategori Umum</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line text-success"></i>
                    Hasil Ranking PROMETHEE Kategori Umum
                </h3>
                <div class="card-tools">
                    <a href="{{ route('panitia.promethee.umum.form') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-redo"></i> Proses Ulang
                    </a>
                    <a href="{{ route('panitia.promethee.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(!empty($results))
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Informasi Hasil</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Total Siswa:</strong> {{ count($results) }}
                            </div>
                            <div class="col-md-4">
                                <strong>Sudah Divalidasi:</strong> {{ collect($results)->where('status_validasi', '!=', 'pending')->count() }}
                            </div>
                            <div class="col-md-4">
                                <strong>Status:</strong> <span class="badge badge-success">Proses Selesai</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="resultsTable">
                            <thead>
                                <tr>
                                    <th>Ranking</th>
                                    <th>No. Pendaftaran</th>
                                    <th>Nama Siswa</th>
                                    <th>Pilihan Jurusan</th>
                                    <th>Phi+</th>
                                    <th>Phi-</th>
                                    <th>Phi Net</th>
                                    <th>Status Validasi</th>
                                    <th>Jurusan Diterima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary">#{{ $result['ranking'] }}</span>
                                        </td>
                                        <td>{{ $result['siswa']['no_pendaftaran'] }}</td>
                                        <td>{{ $result['siswa']['nama_lengkap'] }}</td>
                                        <td>
                                            @if(isset($result['siswa']['pilihan_jurusan1']))
                                                <span class="badge badge-primary">{{ $result['siswa']['pilihan_jurusan1']['kode_jurusan'] }}</span>
                                                {{ $result['siswa']['pilihan_jurusan1']['nama_jurusan'] }}
                                            @endif
                                            @if(isset($result['siswa']['pilihan_jurusan2']) && $result['siswa']['pilihan_jurusan2'])
                                                <br><small class="text-muted">
                                                    Pilihan 2: <span class="badge badge-secondary">{{ $result['siswa']['pilihan_jurusan2']['kode_jurusan'] }}</span>
                                                    {{ $result['siswa']['pilihan_jurusan2']['nama_jurusan'] }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($result['phi_plus'], 4) }}</td>
                                        <td>{{ number_format($result['phi_minus'], 4) }}</td>
                                        <td>
                                            <strong>{{ number_format($result['phi_net'], 4) }}</strong>
                                        </td>
                                        <td>
                                            @switch($result['status_validasi'])
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
                                        <td>
                                            @if(isset($result['siswa']['jurusan_diterima']) && $result['siswa']['jurusan_diterima'])
                                                <span class="badge badge-success">{{ $result['siswa']['jurusan_diterima']['kode_jurusan'] }}</span>
                                                {{ $result['siswa']['jurusan_diterima']['nama_jurusan'] }}
                                            @else
                                                <span class="text-muted">Belum ditentukan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <div class="alert alert-success">
                            <h5><i class="icon fas fa-check"></i> Proses Selesai</h5>
                            <p>Proses PROMETHEE untuk kategori umum telah selesai. Hasil telah dikirim ke masing-masing Ketua Jurusan untuk validasi akhir.</p>
                            <p>Ketua Jurusan akan menentukan penempatan akhir siswa berdasarkan kuota dan hasil ranking.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Statistik Validasi</h3>
                                </div>
                                <div class="card-body">
                                    @php
                                        $stats = collect($results);
                                        $lulus = $stats->where('status_validasi', 'lulus')->count();
                                        $lulusPilihan2 = $stats->where('status_validasi', 'lulus_pilihan_2')->count();
                                        $tidakLulus = $stats->where('status_validasi', 'tidak_lulus')->count();
                                        $pending = $stats->where('status_validasi', 'pending')->count();
                                    @endphp
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="description-block border-right">
                                                <h5 class="description-header text-success">{{ $lulus }}</h5>
                                                <span class="description-text">Lulus</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="description-block">
                                                <h5 class="description-header text-info">{{ $lulusPilihan2 }}</h5>
                                                <span class="description-text">Lulus Pilihan 2</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="description-block border-right">
                                                <h5 class="description-header text-danger">{{ $tidakLulus }}</h5>
                                                <span class="description-text">Tidak Lulus</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="description-block">
                                                <h5 class="description-header text-warning">{{ $pending }}</h5>
                                                <span class="description-text">Pending</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Aksi Selanjutnya</h3>
                                </div>
                                <div class="card-body">
                                    <a href="#" class="btn btn-primary btn-block">
                                        <i class="fas fa-print"></i> Cetak Laporan Hasil
                                    </a>
                                    <a href="#" class="btn btn-success btn-block">
                                        <i class="fas fa-file-excel"></i> Export ke Excel
                                    </a>
                                    <a href="{{ route('panitia.siswa.index') }}" class="btn btn-info btn-block">
                                        <i class="fas fa-users"></i> Lihat Data Siswa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Belum Ada Hasil</h5>
                        <p>Belum ada hasil PROMETHEE untuk kategori umum.</p>
                        <a href="{{ route('panitia.promethee.umum.form') }}" class="btn btn-success">
                            <i class="fas fa-play"></i> Jalankan PROMETHEE Umum
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resultsTable').DataTable({
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
