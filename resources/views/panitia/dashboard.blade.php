@extends('adminlte::page')

@section('title', 'Dashboard Panitia PPDB')

@section('content_header')
    <h1>Dashboard Panitia PPDB</h1>
@stop

@section('content')
@php
    $tahunAkademik = \App\Models\TahunAkademik::getActive();
    $totalPendaftar = $tahunAkademik ? \App\Models\Siswa::where('tahun_akademik_id', $tahunAkademik->id)->count() : 0;
    $khususCount = $tahunAkademik ? \App\Models\Siswa::where('tahun_akademik_id', $tahunAkademik->id)->where('kategori', 'khusus')->count() : 0;
    $umumCount = $tahunAkademik ? \App\Models\Siswa::where('tahun_akademik_id', $tahunAkademik->id)->where('kategori', 'umum')->count() : 0;
    $lulusCount = $tahunAkademik ? \App\Models\Siswa::where('tahun_akademik_id', $tahunAkademik->id)->where('status_seleksi', 'lulus')->count() : 0;
@endphp
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalPendaftar }}</h3>
                <p>Total Pendaftar</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="{{ route('panitia.siswa.index') }}" class="small-box-footer">
                Kelola Data Siswa <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $khususCount }}</h3>
                <p>Kategori Khusus</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
            <a href="{{ route('panitia.promethee.index') }}" class="small-box-footer">
                Proses Khusus <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $umumCount }}</h3>
                <p>Kategori Umum</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('panitia.promethee.index') }}" class="small-box-footer">
                Proses Umum <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $lulusCount }}</h3>
                <p>Sudah Lulus</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('panitia.reports.index') }}" class="small-box-footer">
                Lihat Hasil <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status Proses Seleksi</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Kategori Khusus (TAB/TSM)</h5>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 0%">
                                Belum Dimulai
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Kategori Umum</h5>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%">
                                Tidak Aktif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Selamat Datang!</h3>
            </div>
            <div class="card-body">
                <p>Halo, <strong>{{ Auth::user()->name }}</strong>!</p>
                <p>Anda dapat:</p>
                <ul>
                    <li>Mengelola data calon siswa</li>
                    <li>Input nilai wawancara & TPA</li>
                    <li>Menjalankan proses PROMETHEE</li>
                    <li>Mencetak laporan hasil</li>
                </ul>
                <a href="{{ route('panitia.reports.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-file-alt"></i> Laporan & Cetak
                </a>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
