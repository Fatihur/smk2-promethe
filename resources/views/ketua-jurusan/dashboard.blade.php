@extends('adminlte::page')

@section('title', 'Dashboard Ketua Jurusan')

@section('content_header')
    <h1>Dashboard Ketua Jurusan</h1>
@stop

@section('content')
@php
    $statusService = new \App\Services\StatusTrackingService();
    $stats = $statusService->getKetuaJurusanStats(Auth::user());
@endphp
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_pendaftar'] ?? 0 }}</h3>
                <p>Pendaftar Jurusan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="#" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['pending_validation'] ?? 0 }}</h3>
                <p>Menunggu Validasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('ketua-jurusan.validation.index') }}" class="small-box-footer">
                Validasi Sekarang <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['validated'] ?? 0 }}</h3>
                <p>Sudah Divalidasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
            <a href="{{ route('ketua-jurusan.validation.index') }}" class="small-box-footer">
                Lihat Hasil <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['kuota'] ?? 0 }}</h3>
                <p>Kuota Jurusan</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">
                Info Kuota <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Selamat Datang, Ketua Jurusan {{ Auth::user()->jurusan->nama_jurusan ?? 'Belum Ditentukan' }}!
                </h3>
            </div>
            <div class="card-body">
                <p>Halo, <strong>{{ Auth::user()->name }}</strong>!</p>
                <p>Sebagai Ketua Jurusan, Anda bertanggung jawab untuk:</p>
                <ul>
                    <li>Memvalidasi hasil seleksi siswa yang masuk ke jurusan Anda</li>
                    <li>Menentukan status kelulusan: <strong>Lulus</strong>, <strong>Lulus Pilihan Kedua</strong>, atau <strong>Tidak Lulus</strong></li>
                    <li>Memberikan catatan validasi jika diperlukan</li>
                    <li>Memantau perkembangan proses seleksi untuk jurusan Anda</li>
                </ul>
                
                @if(Auth::user()->jurusan)
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Informasi Jurusan</h5>
                    <strong>Kode:</strong> {{ Auth::user()->jurusan->kode_jurusan }}<br>
                    <strong>Nama:</strong> {{ Auth::user()->jurusan->nama_jurusan }}<br>
                    <strong>Kuota:</strong> {{ Auth::user()->jurusan->kuota }} siswa
                </div>
                @else
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                    Anda belum ditugaskan ke jurusan tertentu. Silakan hubungi administrator.
                </div>
                @endif
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
