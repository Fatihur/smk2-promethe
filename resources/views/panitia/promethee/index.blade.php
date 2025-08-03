@extends('adminlte::page')

@section('title', 'PROMETHEE Dashboard')

@section('content_header')
    <h1>PROMETHEE Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_siswa'] }}</h3>
                <p>Total Pendaftar</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['khusus_count'] }}</h3>
                <p>Kategori Khusus</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['umum_count'] }}</h3>
                <p>Kategori Umum</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $tahunAkademik->tahun }}</h3>
                <p>Tahun Akademik</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star text-warning"></i>
                    Kategori Khusus (TAB/TSM)
                </h3>
            </div>
            <div class="card-body">
                <p>Proses seleksi untuk jurusan Teknik Alat Berat (TAB) dan Teknik Sepeda Motor (TSM).</p>
                
                <div class="row">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <h5 class="description-header">{{ $stats['khusus_count'] }}</h5>
                            <span class="description-text">Pendaftar</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <h5 class="description-header">0</h5>
                            <span class="description-text">Sudah Diproses</span>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('panitia.promethee.khusus.form') }}" class="btn btn-warning btn-block">
                        <i class="fas fa-play"></i> Mulai Proses Khusus
                    </a>
                    <a href="{{ route('panitia.promethee.khusus.results') }}" class="btn btn-outline-warning btn-block">
                        <i class="fas fa-chart-line"></i> Lihat Hasil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users text-success"></i>
                    Kategori Umum
                </h3>
            </div>
            <div class="card-body">
                <p>Proses seleksi untuk semua jurusan lainnya dan siswa yang tidak lolos kategori khusus.</p>

                <!-- Status Validasi Khusus -->
                @if(isset($khususValidationStatus))
                    @if($khususValidationStatus['can_proceed'])
                        <div class="alert alert-success alert-sm">
                            <i class="fas fa-check-circle"></i>
                            <strong>Siap Diproses:</strong> Validasi kategori khusus selesai
                        </div>
                    @else
                        <div class="alert alert-warning alert-sm">
                            <i class="fas fa-clock"></i>
                            <strong>Menunggu:</strong> {{ $khususValidationStatus['pending_validations'] }} validasi khusus
                        </div>
                    @endif
                @endif

                <div class="row">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <h5 class="description-header">{{ $stats['umum_count'] }}</h5>
                            <span class="description-text">Pendaftar</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <h5 class="description-header">0</h5>
                            <span class="description-text">Sudah Diproses</span>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    @if(isset($khususValidationStatus) && $khususValidationStatus['can_proceed'])
                        <a href="{{ route('panitia.promethee.umum.form') }}" class="btn btn-success btn-block">
                            <i class="fas fa-play"></i> Mulai Proses Umum
                        </a>
                    @else
                        <button class="btn btn-secondary btn-block" disabled>
                            <i class="fas fa-lock"></i> Proses Umum Terkunci
                        </button>
                    @endif
                    <a href="{{ route('panitia.promethee.umum.results') }}" class="btn btn-outline-success btn-block">
                        <i class="fas fa-chart-line"></i> Lihat Hasil
                    </a>
                </div>

                <div class="mt-2">
                    <form action="{{ route('panitia.promethee.transfer.umum') }}" method="POST"
                          onsubmit="return confirm('Yakin ingin memindahkan siswa yang tidak lolos kategori khusus ke kategori umum?')">
                        @csrf
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-exchange-alt"></i> Transfer dari Khusus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Alur Proses PROMETHEE</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="time-label">
                        <span class="bg-red">Tahap 1: Kategori Khusus</span>
                    </div>
                    <div>
                        <i class="fas fa-star bg-yellow"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">Proses PROMETHEE Khusus</h3>
                            <div class="timeline-body">
                                Siswa dengan pilihan 1 TAB/TSM diproses menggunakan PROMETHEE.
                                Tentukan kuota (contoh: 80 dari 100 pendaftar).
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-check bg-green"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">Validasi Ketua Jurusan</h3>
                            <div class="timeline-body">
                                80 siswa teratas divalidasi oleh Ketua Jurusan TAB/TSM:
                                <strong>Lulus</strong>, <strong>Lulus Pilihan 2</strong>, atau <strong>Tidak Lulus</strong>.
                            </div>
                        </div>
                    </div>
                    <div class="time-label">
                        <span class="bg-green">Tahap 2: Kategori Umum</span>
                    </div>
                    <div>
                        <i class="fas fa-exchange-alt bg-blue"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">Transfer Siswa</h3>
                            <div class="timeline-body">
                                Siswa yang tidak masuk 80 besar otomatis dipindah ke Kategori Umum
                                dengan pilihan jurusan 2 sebagai pilihan utama.
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-users bg-green"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">Proses PROMETHEE Umum</h3>
                            <div class="timeline-body">
                                Semua siswa kategori umum (termasuk transfer dari khusus) diproses
                                menggunakan PROMETHEE untuk penempatan akhir.
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-flag bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    {{-- Add here extra scripts --}}
@stop
