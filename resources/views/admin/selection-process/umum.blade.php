@extends('adminlte::page')

@section('title', 'Proses Seleksi Kategori Umum')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Proses Seleksi Kategori Umum</h1>
        <div>
            <span class="badge badge-info">{{ $tahunAkademik->nama_tahun }}</span>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Overview Kategori Umum</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{ route('admin.selection-process.khusus') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-star"></i> Khusus
                        </a>
                        <a href="{{ route('admin.selection-process.umum') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-users"></i> Umum
                        </a>
                        <a href="{{ route('admin.selection-process.monitor') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Monitor
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Kategori Umum</strong> mencakup semua jurusan selain TAB dan TSM 
                    yang menggunakan kriteria seleksi standar.
                </div>

                @if(count($statistics) > 0)
                    <div class="row">
                        @foreach($statistics as $stat)
                            <div class="col-md-6 col-lg-4">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-graduation-cap"></i>
                                            {{ $stat['jurusan']->nama_jurusan }}
                                        </h3>
                                        <div class="card-tools">
                                            <span class="badge badge-primary">{{ $stat['jurusan']->kode_jurusan }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="description-block border-right">
                                                    <span class="description-percentage text-success">
                                                        <i class="fas fa-users"></i>
                                                    </span>
                                                    <h5 class="description-header">{{ $stat['total_pendaftar'] }}</h5>
                                                    <span class="description-text">PENDAFTAR</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="description-block">
                                                    <span class="description-percentage text-info">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                    <h5 class="description-header">{{ $stat['diterima'] }}</h5>
                                                    <span class="description-text">DITERIMA</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="progress mt-3">
                                            <div class="progress-bar bg-primary" 
                                                 style="width: {{ $stat['progress'] }}%">
                                                {{ $stat['progress'] }}%
                                            </div>
                                        </div>
                                        <small class="text-muted">Progress Seleksi</small>

                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">Kuota:</small>
                                                    <strong>{{ $stat['kuota'] }}</strong>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Sisa Kuota:</small>
                                                    <strong class="text-{{ $stat['kuota'] - $stat['diterima'] > 0 ? 'success' : 'danger' }}">
                                                        {{ $stat['kuota'] - $stat['diterima'] }}
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Diproses: {{ $stat['sudah_diproses'] }}</small>
                                            </div>
                                            <div class="col-6 text-right">
                                                @if($stat['kuota'] - $stat['diterima'] <= 0)
                                                    <span class="badge badge-success">PENUH</span>
                                                @else
                                                    <span class="badge badge-primary">TERSEDIA</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Jurusan Kategori Umum</h5>
                        <p class="text-muted">Silakan tambahkan jurusan dengan kategori umum terlebih dahulu.</p>
                        <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Jurusan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(count($statistics) > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ringkasan Kategori Umum</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-graduation-cap"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Jurusan</span>
                                <span class="info-box-number">{{ count($statistics) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Pendaftar</span>
                                <span class="info-box-number">{{ collect($statistics)->sum('total_pendaftar') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Diterima</span>
                                <span class="info-box-number">{{ collect($statistics)->sum('diterima') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-clipboard-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Kuota</span>
                                <span class="info-box-number">{{ collect($statistics)->sum('kuota') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
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
