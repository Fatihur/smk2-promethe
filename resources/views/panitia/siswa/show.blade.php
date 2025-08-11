@extends('adminlte::page')

@section('title', 'Detail Siswa')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Siswa</h1>
        <div>
            <a href="{{ route('panitia.siswa.edit', $siswa) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('panitia.siswa.nilai.edit', $siswa) }}" class="btn btn-success">
                <i class="fas fa-clipboard-list"></i> Input Nilai
            </a>
            <a href="{{ route('panitia.siswa.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <!-- Data Pribadi -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user"></i> Data Pribadi
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">No. Pendaftaran</th>
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
                        <th>Jenis Kelamin</th>
                        <td>: {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <td>: {{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>: {{ $siswa->alamat ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>: {{ $siswa->no_telepon ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>: {{ $siswa->email ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ayah</th>
                        <td>: {{ $siswa->nama_ayah ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Asal Sekolah</th>
                        <td>: {{ $siswa->asal_sekolah }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Data Seleksi -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-graduation-cap"></i> Data Seleksi
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Tahun Akademik</th>
                        <td>: {{ $siswa->tahunAkademik->tahun ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pilihan Jurusan 1</th>
                        <td>: 
                            @if($siswa->pilihanJurusan1)
                                <span class="badge badge-primary">{{ $siswa->pilihanJurusan1->kode_jurusan }}</span>
                                {{ $siswa->pilihanJurusan1->nama_jurusan }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Pilihan Jurusan 2</th>
                        <td>: 
                            @if($siswa->pilihanJurusan2)
                                <span class="badge badge-info">{{ $siswa->pilihanJurusan2->kode_jurusan }}</span>
                                {{ $siswa->pilihanJurusan2->nama_jurusan }}
                            @else
                                -
                            @endif
                        </td>
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
                    <tr>
                        <th>Status Seleksi</th>
                        <td>: 
                            @switch($siswa->status_seleksi)
                                @case('pending')
                                    <span class="badge badge-secondary">Pending</span>
                                    @break
                                @case('lulus')
                                    <span class="badge badge-success">Lulus</span>
                                    @break
                                @case('tidak_lulus')
                                    <span class="badge badge-danger">Tidak Lulus</span>
                                    @break
                                @case('lulus_pilihan_2')
                                    <span class="badge badge-warning">Lulus Pilihan 2</span>
                                    @break
                                @default
                                    <span class="badge badge-secondary">{{ ucfirst($siswa->status_seleksi) }}</span>
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>Jurusan Diterima</th>
                        <td>: 
                            @if($siswa->jurusanDiterima)
                                <span class="badge badge-success">{{ $siswa->jurusanDiterima->kode_jurusan }}</span>
                                {{ $siswa->jurusanDiterima->nama_jurusan }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Daftar</th>
                        <td>: {{ $siswa->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>: {{ $siswa->updated_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Nilai Siswa -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line"></i> Nilai Siswa
                </h3>
                <div class="card-tools">
                    <a href="{{ route('panitia.siswa.nilai.edit', $siswa) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Input/Edit Nilai
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($siswa->nilaiSiswa->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa->nilaiSiswa as $nilai)
                                    <tr>
                                        <td>
                                            <span class="badge badge-secondary">{{ $nilai->masterKriteria->kode_kriteria ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $nilai->masterKriteria->nama_kriteria ?? 'N/A' }}</strong>
                                            @if($nilai->masterKriteria && $nilai->masterKriteria->deskripsi)
                                                <br><small class="text-muted">{{ $nilai->masterKriteria->deskripsi }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-primary badge-lg">{{ number_format($nilai->nilai, 2) }}</span>
                                        </td>
                                        <td>{{ $nilai->keterangan ?? '-' }}</td>
                                        <td>{{ $nilai->updated_at->format('d/m/Y H:i') }}</td>
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
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .card-title {
        font-weight: 600;
    }
</style>
@stop
