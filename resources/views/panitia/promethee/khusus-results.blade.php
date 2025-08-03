@extends('adminlte::page')

@section('title', 'Hasil PROMETHEE Khusus')

@section('content_header')
    <h1>Hasil PROMETHEE Kategori Khusus</h1>
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
                    <i class="fas fa-chart-line text-warning"></i>
                    Hasil Ranking PROMETHEE Kategori Khusus
                </h3>
                <div class="card-tools">
                    <a href="{{ route('panitia.promethee.khusus.form') }}" class="btn btn-warning btn-sm">
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
                            <div class="col-md-3">
                                <strong>Total Siswa:</strong> {{ count($results) }}
                            </div>
                            <div class="col-md-3">
                                <strong>Dalam Kuota:</strong> {{ collect($results)->where('masuk_kuota', true)->count() }}
                            </div>
                            <div class="col-md-3">
                                <strong>Luar Kuota:</strong> {{ collect($results)->where('masuk_kuota', false)->count() }}
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong> <span class="badge badge-warning">Menunggu Validasi</span>
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
                                    <th>Pilihan 1</th>
                                    <th>Pilihan 2</th>
                                    <th>Phi+</th>
                                    <th>Phi-</th>
                                    <th>Phi Net</th>
                                    <th>Status Kuota</th>
                                    <th>Status Validasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                    <tr class="{{ $result['masuk_kuota'] ? 'table-success' : 'table-light' }}">
                                        <td>
                                            <span class="badge {{ $result['masuk_kuota'] ? 'badge-success' : 'badge-secondary' }}">
                                                #{{ $result['ranking'] }}
                                            </span>
                                        </td>
                                        <td>{{ $result['siswa']['no_pendaftaran'] }}</td>
                                        <td>{{ $result['siswa']['nama_lengkap'] }}</td>
                                        <td>
                                            @if(isset($result['siswa']['pilihan_jurusan1']))
                                                <span class="badge badge-primary">{{ $result['siswa']['pilihan_jurusan1']['kode_jurusan'] }}</span>
                                                {{ $result['siswa']['pilihan_jurusan1']['nama_jurusan'] }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($result['siswa']['pilihan_jurusan2']) && $result['siswa']['pilihan_jurusan2'])
                                                <span class="badge badge-secondary">{{ $result['siswa']['pilihan_jurusan2']['kode_jurusan'] }}</span>
                                                {{ $result['siswa']['pilihan_jurusan2']['nama_jurusan'] }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($result['phi_plus'], 4) }}</td>
                                        <td>{{ number_format($result['phi_minus'], 4) }}</td>
                                        <td>
                                            <strong>{{ number_format($result['phi_net'], 4) }}</strong>
                                        </td>
                                        <td>
                                            @if($result['masuk_kuota'])
                                                <span class="badge badge-success">Dalam Kuota</span>
                                            @else
                                                <span class="badge badge-secondary">Luar Kuota</span>
                                            @endif
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <div class="alert alert-warning">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Langkah Selanjutnya</h5>
                            <p>Setelah hasil PROMETHEE selesai:</p>
                            <ol>
                                <li>Siswa <strong>dalam kuota</strong> akan dikirim ke Ketua Jurusan untuk validasi</li>
                                <li>Ketua Jurusan akan menentukan status: <strong>Lulus</strong>, <strong>Lulus Pilihan 2</strong>, atau <strong>Tidak Lulus</strong></li>
                                <li>Siswa <strong>luar kuota</strong> akan otomatis dipindah ke kategori umum</li>
                                <li>Setelah validasi selesai, lanjutkan ke proses kategori umum</li>
                            </ol>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Belum Ada Hasil</h5>
                        <p>Belum ada hasil PROMETHEE untuk kategori khusus.</p>
                        <a href="{{ route('panitia.promethee.khusus.form') }}" class="btn btn-warning">
                            <i class="fas fa-play"></i> Jalankan PROMETHEE Khusus
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
    <style>
        .table-success {
            background-color: rgba(40, 167, 69, 0.1) !important;
        }
    </style>
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
