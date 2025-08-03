@extends('adminlte::page')

@section('title', 'Proses PROMETHEE Umum')

@section('content_header')
    <h1>Proses PROMETHEE Kategori Umum</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users text-success"></i>
                    Form Proses PROMETHEE Umum
                </h3>
                <div class="card-tools">
                    <a href="{{ route('panitia.promethee.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('panitia.promethee.umum.run') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Informasi Proses</h5>
                        <p>Proses PROMETHEE untuk kategori umum akan:</p>
                        <ul>
                            <li>Menghitung ranking untuk <strong>{{ $umumCount }} siswa</strong> kategori umum</li>
                            <li>Termasuk siswa yang dipindah dari kategori khusus</li>
                            <li>Menggunakan metode PROMETHEE dengan preference function "usual"</li>
                            <li>Menentukan penempatan akhir berdasarkan kuota masing-masing jurusan</li>
                            <li>Hasil akan dikirim ke ketua jurusan untuk validasi akhir</li>
                        </ul>
                    </div>

                    <!-- Status Validasi Khusus -->
                    @if(isset($khususValidationStatus))
                        @if($khususValidationStatus['can_proceed'])
                            <div class="alert alert-success">
                                <h5><i class="icon fas fa-check-circle"></i> Status Validasi Kategori Khusus</h5>
                                <p>{{ $khususValidationStatus['message'] }}</p>
                                @if($khususValidationStatus['has_khusus_results'])
                                    <ul class="mb-0">
                                        <li><strong>Total siswa dalam kuota:</strong> {{ $khususValidationStatus['total_in_kuota'] ?? 0 }}</li>
                                        <li><strong>Sudah divalidasi:</strong> {{ $khususValidationStatus['completed_validations'] }}</li>
                                        <li><strong>Menunggu validasi:</strong> {{ $khususValidationStatus['pending_validations'] }}</li>
                                    </ul>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <h5><i class="icon fas fa-times-circle"></i> Status Validasi Kategori Khusus</h5>
                                <p>{{ $khususValidationStatus['message'] }}</p>
                                @if($khususValidationStatus['has_khusus_results'])
                                    <ul class="mb-0">
                                        <li><strong>Total siswa dalam kuota:</strong> {{ $khususValidationStatus['total_in_kuota'] ?? 0 }}</li>
                                        <li><strong>Sudah divalidasi:</strong> {{ $khususValidationStatus['completed_validations'] }}</li>
                                        <li><strong>Menunggu validasi:</strong> {{ $khususValidationStatus['pending_validations'] }}</li>
                                    </ul>
                                @endif
                            </div>
                        @endif
                    @endif

                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Informasi Proses</h5>
                        <p>Proses PROMETHEE untuk kategori umum akan:</p>
                        <ul>
                            <li>Menghitung ranking untuk <strong>{{ $umumCount }} siswa</strong> kategori umum</li>
                            <li>Termasuk siswa yang dipindah dari kategori khusus</li>
                            <li>Menggunakan metode PROMETHEE dengan preference function "usual"</li>
                            <li>Menentukan penempatan akhir berdasarkan kuota masing-masing jurusan</li>
                            <li>Hasil akan dikirim ke ketua jurusan untuk validasi akhir</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Persyaratan</h5>
                        <p>Sebelum menjalankan proses ini, pastikan:</p>
                        <ul>
                            <li>Proses kategori khusus sudah selesai dan divalidasi</li>
                            <li>Siswa yang gagal kategori khusus sudah dipindah ke umum</li>
                            <li>Semua siswa kategori umum sudah memiliki nilai lengkap</li>
                        </ul>
                    </div>
                </div>
                <div class="card-footer">
                    @if(isset($khususValidationStatus) && $khususValidationStatus['can_proceed'])
                        <button type="submit" class="btn btn-success"
                                onclick="return confirm('Yakin ingin menjalankan proses PROMETHEE untuk kategori umum?')">
                            <i class="fas fa-play"></i> Jalankan PROMETHEE Umum
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class="fas fa-lock"></i> PROMETHEE Umum Terkunci
                        </button>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i>
                            Proses PROMETHEE umum tidak dapat dijalankan sampai validasi kategori khusus selesai.
                        </small>
                    @endif
                    <a href="{{ route('panitia.promethee.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Kategori Umum</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="description-block">
                            <h5 class="description-header">{{ $umumCount }}</h5>
                            <span class="description-text">Total Pendaftar Umum</span>
                        </div>
                    </div>
                </div>

                <hr>

                <h6>Jurusan Kategori Umum:</h6>
                <ul class="list-unstyled text-sm">
                    <li><i class="fas fa-car text-info"></i> Teknik Kendaraan Ringan (TKR)</li>
                    <li><i class="fas fa-wrench text-info"></i> Teknik Body dan Sepeda Motor (TBSM)</li>
                    <li><i class="fas fa-laptop text-info"></i> Teknik Komputer dan Jaringan (TKJ)</li>
                    <li><i class="fas fa-code text-info"></i> Rekayasa Perangkat Lunak (RPL)</li>
                    <li><i class="fas fa-camera text-info"></i> Multimedia (MM)</li>
                    <li><i class="fas fa-drafting-compass text-info"></i> Teknik Gambar Bangunan (TGB)</li>
                    <li><i class="fas fa-building text-info"></i> Teknik Konstruksi Batu dan Beton (TKBB)</li>
                    <li><i class="fas fa-fire text-info"></i> Teknik Pengelasan dan Fabrikasi Logam (TPFL)</li>
                    <li><i class="fas fa-cogs text-info"></i> Teknik Mesin (TM)</li>
                    <li><i class="fas fa-bolt text-info"></i> Teknik Instalasi Tenaga Listrik (TITL)</li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tahun Akademik</h3>
            </div>
            <div class="card-body">
                <p><strong>Tahun:</strong> {{ $tahunAkademik->tahun }}</p>
                <p><strong>Semester:</strong> {{ $tahunAkademik->semester }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge badge-success">Aktif</span>
                </p>
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
