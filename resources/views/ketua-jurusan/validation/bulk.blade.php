@extends('layouts.app')

@section('title', 'Validasi Massal - Ketua Jurusan')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Validasi Massal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('ketua-jurusan.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ketua-jurusan.validation.index') }}">Validasi</a></li>
                        <li class="breadcrumb-item active">Validasi Massal</li>
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
                                <i class="fas fa-check-double mr-2"></i>
                                Validasi Massal Hasil Seleksi
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Info:</strong> Fitur validasi massal memungkinkan Anda untuk memvalidasi beberapa hasil seleksi sekaligus.
                            </div>
                            
                            <form action="{{ route('ketua-jurusan.validation.bulk.validate') }}" method="POST">
                                @csrf
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="status_validasi" class="form-label">Status Validasi</label>
                                        <select name="status_validasi" id="status_validasi" class="form-control" required>
                                            <option value="">Pilih Status</option>
                                            <option value="lulus">Lulus</option>
                                            <option value="lulus_pilihan_kedua">Lulus Pilihan Kedua</option>
                                            <option value="tidak_lulus">Tidak Lulus</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="catatan_validasi" class="form-label">Catatan Validasi (Opsional)</label>
                                        <textarea name="catatan_validasi" id="catatan_validasi" class="form-control" rows="3" placeholder="Masukkan catatan jika diperlukan"></textarea>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="50">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="select-all">
                                                        <label class="custom-control-label" for="select-all"></label>
                                                    </div>
                                                </th>
                                                <th>No. Pendaftaran</th>
                                                <th>Nama Siswa</th>
                                                <th>Asal Sekolah</th>
                                                <th>Total Skor</th>
                                                <th>Status Saat Ini</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data akan dimuat dari controller -->
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="alert alert-warning mb-0">
                                                        <i class="fas fa-info-circle"></i>
                                                        Fitur validasi massal sedang dalam pengembangan.
                                                        <br>
                                                        Silakan gunakan <a href="{{ route('ketua-jurusan.validation.index') }}">validasi individual</a> untuk sementara.
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary" disabled>
                                            <i class="fas fa-check-double"></i> Validasi Terpilih
                                        </button>
                                        <a href="{{ route('ketua-jurusan.validation.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Kembali
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Select all checkbox functionality
    $('#select-all').on('change', function() {
        $('input[name="selected_results[]"]').prop('checked', this.checked);
    });
    
    // Individual checkbox change
    $('input[name="selected_results[]"]').on('change', function() {
        if (!this.checked) {
            $('#select-all').prop('checked', false);
        } else {
            if ($('input[name="selected_results[]"]:checked').length === $('input[name="selected_results[]"]').length) {
                $('#select-all').prop('checked', true);
            }
        }
    });
});
</script>
@endpush
