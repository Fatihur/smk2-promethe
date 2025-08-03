@extends('layouts.app')

@section('title', 'Laporan - Ketua Jurusan')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('ketua-jurusan.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Laporan Hasil Seleksi
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Info:</strong> Halaman laporan untuk ketua jurusan sedang dalam pengembangan.
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-outline card-primary">
                                        <div class="card-header">
                                            <h5 class="card-title">Laporan Validasi</h5>
                                        </div>
                                        <div class="card-body">
                                            <p>Lihat laporan hasil validasi yang telah dilakukan.</p>
                                            <a href="{{ route('ketua-jurusan.validation.index') }}" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> Lihat Validasi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card card-outline card-success">
                                        <div class="card-header">
                                            <h5 class="card-title">Statistik Jurusan</h5>
                                        </div>
                                        <div class="card-body">
                                            <p>Lihat statistik dan analisis data jurusan.</p>
                                            <button class="btn btn-success" disabled>
                                                <i class="fas fa-chart-pie"></i> Segera Hadir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
