@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1>Dashboard Administrator</h1>
@stop

@section('content')
@php
    $totalJurusan = \App\Models\Jurusan::count();
    $totalUsers = \App\Models\User::count();
    $totalKriteria = \App\Models\MasterKriteria::count();
    $activeTahunAkademik = \App\Models\TahunAkademik::where('is_active', true)->count();
@endphp
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalJurusan }}</h3>
                <p>Total Jurusan</p>
            </div>
            <div class="icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <a href="{{ route('admin.jurusan.index') }}" class="small-box-footer">
                Kelola Jurusan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalUsers }}</h3>
                <p>Total Pengguna</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">
                Kelola Pengguna <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalKriteria }}</h3>
                <p>Kriteria Penilaian</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <a href="{{ route('admin.master-kriteria.index') }}" class="small-box-footer">
                Kelola Kriteria <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $activeTahunAkademik }}</h3>
                <p>Tahun Akademik Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <a href="{{ route('admin.tahun-akademik.index') }}" class="small-box-footer">
                Kelola Tahun Akademik <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h3>
            </div>
            <div class="card-body">
                <p>Anda masuk sebagai <strong>Administrator</strong> sistem SMK2 PROMETHEE.</p>
                <p>Sebagai administrator, Anda dapat:</p>
                <ul>
                    <li>Mengelola data jurusan dan kuota</li>
                    <li>Mengelola kriteria dan bobot penilaian</li>
                    <li>Mengelola tahun akademik</li>
                    <li>Mengelola akun pengguna (Panitia PPDB dan Ketua Jurusan)</li>
                    <li>Memonitor proses seleksi secara keseluruhan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
<style>
    .small-box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .small-box .icon {
        transition: transform 0.3s ease;
    }

    .small-box:hover .icon {
        transform: scale(1.1);
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // Add animation to small boxes
        $('.small-box').addClass('fade-in');

        // Add click animation
        $('.small-box').on('click', function() {
            $(this).addClass('animate__animated animate__pulse');
            setTimeout(() => {
                $(this).removeClass('animate__animated animate__pulse');
            }, 1000);
        });

        console.log("Admin Dashboard loaded successfully!");
    });
</script>
@endpush
