@extends('adminlte::page')

@section('title', 'Input Nilai Per Jurusan')

@section('content_header')
    <h1>Input Nilai Per Jurusan</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Jurusan</h3>
                @if($tahunAktif)
                    <div class="card-tools">
                        <span class="badge badge-info">Tahun Akademik: {{ $tahunAktif->tahun_akademik }}</span>
                        <a href="{{ route('panitia.nilai-jurusan.setup-data') }}" class="btn btn-sm btn-warning ml-2">
                            <i class="fas fa-database"></i> Setup Data Test
                        </a>
                        <a href="{{ route('panitia.nilai-jurusan.clear-nilai') }}" class="btn btn-sm btn-danger ml-2"
                           onclick="return confirm('Yakin ingin menghapus semua data nilai siswa?')">
                            <i class="fas fa-trash"></i> Clear Nilai
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @if(!$tahunAktif)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Tidak ada tahun akademik yang aktif. Hubungi administrator untuk mengaktifkan tahun akademik.
                    </div>
                @elseif($jurusan->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Belum ada jurusan yang tersedia.
                    </div>
                @else
                    <div class="row">
                        @foreach($jurusan as $j)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-graduation-cap"></i>
                                            {{ $j->nama_jurusan }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="description-block">
                                                    <h5 class="description-header text-primary">{{ $j->total_siswa }}</h5>
                                                    <span class="description-text">Total Siswa</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="description-block">
                                                    <h5 class="description-header text-success">{{ $j->siswa_sudah_dinilai }}</h5>
                                                    <span class="description-text">Sudah Dinilai</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($j->total_siswa > 0)
                                            @php
                                                $progress = round(($j->siswa_sudah_dinilai / $j->total_siswa) * 100);
                                            @endphp
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <small class="text-muted">Progress: {{ $progress }}%</small>
                                        @endif
                                    </div>
                                    <div class="card-footer">
                                        <div class="btn-group w-100" role="group">
                                            <a href="{{ route('panitia.nilai-jurusan.siswa', $j) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-users"></i> Lihat Siswa
                                            </a>
                                            @if($j->total_siswa > 0)
                                                <a href="{{ route('panitia.nilai-jurusan.bulk', $j) }}" 
                                                   class="btn btn-success btn-sm">
                                                    <i class="fas fa-table"></i> Input Massal
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .description-block {
            margin: 0;
        }
        .description-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
        }
        .description-text {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .card-outline {
            border-top: 3px solid;
        }
        .progress {
            height: 8px;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Add tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
