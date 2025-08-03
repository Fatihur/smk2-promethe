@extends('adminlte::page')

@section('title', 'Daftar Siswa Lulus')

@section('content_header')
    <h1>Daftar Siswa Lulus</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-check-circle"></i>
            Daftar Siswa Lulus PPDB {{ $tahunAkademik->tahun }} - {{ $tahunAkademik->semester }}
        </h3>
        <div class="card-tools">
            <a href="{{ route('panitia.reports.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('panitia.reports.print.daftar-lulus', request()->query()) }}" class="btn btn-primary btn-sm" target="_blank">
                <i class="fas fa-print"></i> Cetak
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <label for="jurusan_id">Filter Jurusan:</label>
                    <select name="jurusan_id" id="jurusan_id" class="form-control">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusan as $j)
                            <option value="{{ $j->id }}" {{ $jurusanId == $j->id ? 'selected' : '' }}>
                                {{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>&nbsp;</label><br>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('panitia.reports.daftar-lulus') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Summary -->
        <div class="alert alert-success">
            <div class="row">
                <div class="col-md-4">
                    <strong>Total Lulus:</strong> {{ $siswaLulus->count() }} siswa
                </div>
                <div class="col-md-4">
                    <strong>Lulus Pilihan 1:</strong> {{ $siswaLulus->where('status_seleksi', 'lulus')->count() }} siswa
                </div>
                <div class="col-md-4">
                    <strong>Lulus Pilihan 2:</strong> {{ $siswaLulus->where('status_seleksi', 'lulus_pilihan_2')->count() }} siswa
                </div>
            </div>
        </div>

        @if($siswaLulus->isNotEmpty())
            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="lulusTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Pendaftaran</th>
                            <th>NISN</th>
                            <th>Nama Lengkap</th>
                            <th>L/P</th>
                            <th>Asal Sekolah</th>
                            <th>Pilihan Awal</th>
                            <th>Status Lulus</th>
                            <th>Jurusan Diterima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswaLulus as $index => $s)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $s->no_pendaftaran }}</strong></td>
                                <td>{{ $s->nisn }}</td>
                                <td>{{ $s->nama_lengkap }}</td>
                                <td>{{ $s->jenis_kelamin }}</td>
                                <td>{{ $s->asal_sekolah }}</td>
                                <td>
                                    <strong>P1:</strong> <span class="badge badge-primary">{{ $s->pilihanJurusan1->kode_jurusan }}</span>
                                    {{ $s->pilihanJurusan1->nama_jurusan }}
                                    @if($s->pilihanJurusan2)
                                        <br><strong>P2:</strong> <span class="badge badge-secondary">{{ $s->pilihanJurusan2->kode_jurusan }}</span>
                                        {{ $s->pilihanJurusan2->nama_jurusan }}
                                    @endif
                                </td>
                                <td>
                                    @if($s->status_seleksi == 'lulus')
                                        <span class="badge badge-success">Lulus Pilihan 1</span>
                                    @else
                                        <span class="badge badge-info">Lulus Pilihan 2</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success">{{ $s->jurusanDiterima->kode_jurusan }}</span>
                                    <strong>{{ $s->jurusanDiterima->nama_jurusan }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Statistics by Jurusan -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Statistik Lulus Per Jurusan</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Jurusan</th>
                                            <th>Kuota</th>
                                            <th>Lulus</th>
                                            <th>Sisa Kuota</th>
                                            <th>% Terisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jurusan as $j)
                                            @php
                                                $lulusJurusan = $siswaLulus->where('jurusan_diterima_id', $j->id)->count();
                                                $persentase = $j->kuota > 0 ? round(($lulusJurusan / $j->kuota) * 100, 1) : 0;
                                            @endphp
                                            <tr>
                                                <td><span class="badge badge-primary">{{ $j->kode_jurusan }}</span></td>
                                                <td>{{ $j->nama_jurusan }}</td>
                                                <td>{{ $j->kuota }}</td>
                                                <td><strong>{{ $lulusJurusan }}</strong></td>
                                                <td>{{ max(0, $j->kuota - $lulusJurusan) }}</td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar {{ $persentase >= 100 ? 'bg-success' : ($persentase >= 80 ? 'bg-warning' : 'bg-info') }}" 
                                                             role="progressbar" style="width: {{ min(100, $persentase) }}%">
                                                            {{ $persentase }}%
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
                <h5><i class="icon fas fa-exclamation-triangle"></i> Belum Ada Siswa Lulus</h5>
                <p>Belum ada siswa yang dinyatakan lulus untuk filter yang dipilih.</p>
            </div>
        @endif
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
            $('#lulusTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 25,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
@stop
