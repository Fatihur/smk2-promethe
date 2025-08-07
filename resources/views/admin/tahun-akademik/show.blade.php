@extends('adminlte::page')

@section('title', 'Detail Tahun Akademik')

@section('content_header')
    <h1>Detail Tahun Akademik</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Tahun Akademik</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.tahun-akademik.edit', $tahun_akademik) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('admin.tahun-akademik.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Tahun Akademik:</strong>
                                <p class="text-muted">
                                    {{ $tahun_akademik->tahun }}
                                    @if($tahun_akademik->is_active)
                                        <span class="badge badge-success ml-1">Aktif</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong>Semester:</strong>
                                <p class="text-muted">{{ $tahun_akademik->semester }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong>Tanggal Mulai:</strong>
                                <p class="text-muted">{{ $tahun_akademik->tanggal_mulai->format('d F Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Selesai:</strong>
                                <p class="text-muted">{{ $tahun_akademik->tanggal_selesai->format('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong>Status:</strong>
                                <p class="text-muted">
                                    @if($tahun_akademik->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Tidak Aktif</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong>Durasi:</strong>
                                <p class="text-muted">
                                    {{ $tahun_akademik->tanggal_mulai->diffInDays($tahun_akademik->tanggal_selesai) }} hari
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong>Dibuat:</strong>
                                <p class="text-muted">{{ $tahun_akademik->created_at->format('d F Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Terakhir Diupdate:</strong>
                                <p class="text-muted">{{ $tahun_akademik->updated_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($tahun_akademik->siswa->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Siswa</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No. Pendaftaran</th>
                                            <th>Nama Lengkap</th>
                                            <th>Kategori</th>
                                            <th>Status Seleksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tahun_akademik->siswa->take(10) as $siswa)
                                            <tr>
                                                <td>{{ $siswa->no_pendaftaran }}</td>
                                                <td>{{ $siswa->nama_lengkap }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $siswa->kategori == 'khusus' ? 'warning' : 'info' }}">
                                                        {{ ucfirst($siswa->kategori) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $siswa->status_seleksi == 'diterima' ? 'success' : ($siswa->status_seleksi == 'ditolak' ? 'danger' : 'secondary') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $siswa->status_seleksi)) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($tahun_akademik->siswa->count() > 10)
                                <p class="text-muted mt-2">
                                    Menampilkan 10 dari {{ $tahun_akademik->siswa->count() }} siswa.
                                    <a href="{{ route('panitia.siswa.index', ['tahun_akademik_id' => $tahun_akademik->id]) }}">
                                        Lihat semua siswa
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Statistik</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Siswa</span>
                                <span class="info-box-number">{{ $tahun_akademik->siswa->count() }}</span>
                            </div>
                        </div>

                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-star"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Kategori Khusus</span>
                                <span class="info-box-number">{{ $tahun_akademik->siswa->where('kategori', 'khusus')->count() }}</span>
                            </div>
                        </div>

                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-graduation-cap"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Kategori Umum</span>
                                <span class="info-box-number">{{ $tahun_akademik->siswa->where('kategori', 'umum')->count() }}</span>
                            </div>
                        </div>

                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Diterima</span>
                                <span class="info-box-number">{{ $tahun_akademik->siswa->where('status_seleksi', 'diterima')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$tahun_akademik->is_active)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Aksi</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.tahun-akademik.set-active', $tahun_akademik) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-block" 
                                        onclick="return confirm('Yakin ingin mengaktifkan tahun akademik ini?')">
                                    <i class="fas fa-check"></i> Aktifkan Tahun Akademik
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop
