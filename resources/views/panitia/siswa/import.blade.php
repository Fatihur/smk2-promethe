@extends('layouts.app')

@section('title', 'Import Data Siswa')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Import Data Siswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('panitia.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('panitia.siswa.index') }}">Data Siswa</a></li>
                        <li class="breadcrumb-item active">Import</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-upload"></i>
                                Upload File Excel/CSV
                            </h3>
                        </div>
                        <form action="{{ route('panitia.siswa.import.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="file">Pilih File Excel/CSV</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                            <label class="custom-file-label" for="file">Pilih file...</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Format yang didukung: Excel (.xlsx, .xls) dan CSV (.csv). Maksimal 2MB.
                                    </small>
                                </div>

                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info"></i> Petunjuk Import:</h5>
                                    <ul class="mb-0">
                                        <li>File harus menggunakan format template yang disediakan</li>
                                        <li>Kolom yang wajib diisi: <strong>Pilihan ke-1</strong>, <strong>NISN</strong>, dan <strong>Nama Calon Peserta Didik</strong></li>
                                        <li>Pilihan ke-2 boleh dikosongkan</li>
                                        <li>Format tanggal lahir: <strong>Tempat, DD-MM-YYYY</strong> (contoh: Jakarta, 15-05-2008)</li>
                                        <li>Pilihan jurusan harus sesuai dengan nama jurusan yang tersedia</li>
                                        <li>Nomor pendaftaran akan dibuat otomatis</li>
                                        <li>Jika NISN sudah ada, data akan diperbarui</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Import Data
                                </button>
                                <a href="{{ route('panitia.siswa.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-download"></i>
                                Template Import
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>Download template Excel untuk memudahkan proses import data siswa.</p>
                            
                            <a href="{{ route('panitia.siswa.template') }}" class="btn btn-success btn-block">
                                <i class="fas fa-download"></i> Download Template
                            </a>

                            <hr>

                            <div class="alert alert-warning">
                                <strong>Catatan:</strong> Nomor pendaftaran akan dibuat otomatis oleh sistem dengan format YYYY0001, YYYY0002, dst.
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i>
                                Jurusan Tersedia
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Jurusan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jurusan as $j)
                                        <tr>
                                            <td><span class="badge badge-info">{{ $j->kode_jurusan }}</span></td>
                                            <td>{{ $j->nama_jurusan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Update file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });
});
</script>
@endpush
@endsection
