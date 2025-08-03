@extends('adminlte::page')

@section('title', 'Validasi Hasil Seleksi')

@section('content_header')
    <h1>Validasi Hasil Seleksi</h1>
@stop

@section('content')
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

<div class="alert alert-info">
    <h5><i class="icon fas fa-info"></i> Aturan Validasi</h5>
    <ul class="mb-0">
        <li><strong>Siswa yang dapat divalidasi:</strong> Hanya siswa yang memilih jurusan Anda sebagai <strong>pilihan pertama</strong></li>
        <li><strong>Kategori Umum:</strong> Semua siswa yang memilih jurusan Anda dapat divalidasi</li>
        <li><strong>Kategori Khusus:</strong> Hanya siswa yang <strong>lolos perangkingan</strong> (masuk kuota) yang dapat divalidasi</li>
        <li><strong>Status Validasi:</strong> Menentukan apakah siswa diterima di pilihan 1, pilihan 2, atau tidak diterima</li>
    </ul>
</div>

@if($tahunAkademik)
    <div class="alert alert-success">
        <strong>Tahun Akademik Aktif:</strong> {{ $tahunAkademik->tahun_akademik }}
        <br>
        <strong>Periode:</strong> {{ $tahunAkademik->semester }} {{ $tahunAkademik->tahun_akademik }}
    </div>
@endif

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pendingResults->count() }}</h3>
                <p>Menunggu Validasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $validatedResults->where('status_validasi', 'lulus')->count() }}</h3>
                <p>Lulus</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $validatedResults->where('status_validasi', 'lulus_pilihan_2')->count() }}</h3>
                <p>Lulus Pilihan 2</p>
            </div>
            <div class="icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $validatedResults->where('status_validasi', 'tidak_lulus')->count() }}</h3>
                <p>Tidak Lulus</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

@if($pendingResults->isNotEmpty())
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Menunggu Validasi</h3>
        <div class="card-tools">
            @if($pendingResults->count() > 1)
                <a href="{{ route('ketua-jurusan.validation.bulk.form') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-list"></i> Validasi Massal
                </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>No. Pendaftaran</th>
                        <th>Nama Siswa</th>
                        <th>Kategori</th>
                        <th>Phi Net</th>
                        <th>Status Kuota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingResults as $result)
                        <tr>
                            <td>
                                <span class="badge badge-primary">#{{ $result->ranking }}</span>
                            </td>
                            <td>{{ $result->siswa->no_pendaftaran }}</td>
                            <td>{{ $result->siswa->nama_lengkap }}</td>
                            <td>
                                @if($result->kategori == 'khusus')
                                    <span class="badge badge-warning">Khusus</span>
                                @else
                                    <span class="badge badge-info">Umum</span>
                                @endif
                            </td>
                            <td>{{ number_format($result->phi_net, 4) }}</td>
                            <td>
                                @if($result->masuk_kuota)
                                    <span class="badge badge-success">Dalam Kuota</span>
                                @else
                                    <span class="badge badge-secondary">Luar Kuota</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('ketua-jurusan.validation.show', $result) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Validasi
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($validatedResults->isNotEmpty())
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sudah Divalidasi</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>No. Pendaftaran</th>
                        <th>Nama Siswa</th>
                        <th>Kategori</th>
                        <th>Status Validasi</th>
                        <th>Validator</th>
                        <th>Tanggal Validasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($validatedResults as $result)
                        <tr>
                            <td>
                                <span class="badge badge-primary">#{{ $result->ranking }}</span>
                            </td>
                            <td>{{ $result->siswa->no_pendaftaran }}</td>
                            <td>{{ $result->siswa->nama_lengkap }}</td>
                            <td>
                                @if($result->kategori == 'khusus')
                                    <span class="badge badge-warning">Khusus</span>
                                @else
                                    <span class="badge badge-info">Umum</span>
                                @endif
                            </td>
                            <td>
                                @switch($result->status_validasi)
                                    @case('lulus')
                                        <span class="badge badge-success">Lulus</span>
                                        @break
                                    @case('lulus_pilihan_2')
                                        <span class="badge badge-info">Lulus Pilihan 2</span>
                                        @break
                                    @case('tidak_lulus')
                                        <span class="badge badge-danger">Tidak Lulus</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $result->validator->name ?? '-' }}</td>
                            <td>{{ $result->validated_at ? $result->validated_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <a href="{{ route('ketua-jurusan.validation.show', $result) }}" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($pendingResults->isEmpty() && $validatedResults->isEmpty())
<div class="card">
    <div class="card-body text-center">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <h4>Belum Ada Data Validasi</h4>
        <p class="text-muted">
            Belum ada hasil PROMETHEE yang perlu divalidasi untuk jurusan Anda.
            Silakan tunggu hingga proses PROMETHEE selesai dijalankan oleh Panitia PPDB.
        </p>
    </div>
</div>
@endif
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
@stop
