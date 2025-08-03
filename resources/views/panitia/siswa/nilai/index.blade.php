@extends('adminlte::page')

@section('title', 'Nilai Siswa')

@section('content_header')
    <h1>Nilai Siswa - {{ $siswa->nama_lengkap }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Siswa</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>No. Pendaftaran</th>
                        <td>: {{ $siswa->no_pendaftaran }}</td>
                    </tr>
                    <tr>
                        <th>NISN</th>
                        <td>: {{ $siswa->nisn }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>: {{ $siswa->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>Pilihan 1</th>
                        <td>: {{ $siswa->pilihanJurusan1->nama_jurusan }}</td>
                    </tr>
                    <tr>
                        <th>Pilihan 2</th>
                        <td>: {{ $siswa->pilihanJurusan2->nama_jurusan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>: 
                            @if($siswa->kategori == 'khusus')
                                <span class="badge badge-warning">Khusus</span>
                            @else
                                <span class="badge badge-info">Umum</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Nilai Kriteria</h3>
                <div class="card-tools">
                    <a href="{{ route('panitia.siswa.nilai.edit', $siswa) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit Nilai
                    </a>
                    <a href="{{ route('panitia.siswa.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($nilai->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nilai as $n)
                                    <tr>
                                        <td>
                                            <strong>{{ $n->masterKriteria->nama_kriteria }}</strong>
                                            @if($n->masterKriteria->deskripsi)
                                                <br><small class="text-muted">{{ $n->masterKriteria->deskripsi }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-primary badge-lg">{{ number_format($n->nilai, 2) }}</span>
                                        </td>
                                        <td>{{ $n->keterangan ?? '-' }}</td>
                                        <td>{{ $n->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Belum ada nilai yang diinput untuk siswa ini.
                        <a href="{{ route('panitia.siswa.nilai.edit', $siswa) }}" class="btn btn-primary btn-sm ml-2">
                            <i class="fas fa-plus"></i> Input Nilai
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 1.1em;
            padding: 0.5em 0.75em;
        }
    </style>
@stop
