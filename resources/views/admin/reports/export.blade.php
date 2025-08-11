@extends('adminlte::page')

@section('title', 'Export Data')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Export Data</h1>
        <div>
            <span class="badge badge-info">{{ $tahunAkademik->nama_tahun }}</span>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pilih Data untuk Export</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Pilih jenis data yang ingin Anda export. Data akan diunduh dalam format Excel (.xlsx).
                </div>

                <div class="row">
                    @foreach($exportOptions as $key => $label)
                        <div class="col-md-6 mb-3">
                            <div class="card card-outline card-primary">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-{{ $key == 'siswa' ? 'users' : ($key == 'hasil_seleksi' ? 'clipboard-list' : ($key == 'ranking' ? 'trophy' : 'chart-bar')) }}"></i>
                                        {{ $label }}
                                    </h5>
                                    <p class="card-text">
                                        @switch($key)
                                            @case('siswa')
                                                Export semua data siswa yang terdaftar pada tahun akademik ini.
                                                @break
                                            @case('hasil_seleksi')
                                                Export hasil seleksi lengkap dengan skor dan status kelulusan.
                                                @break
                                            @case('ranking')
                                                Export ranking siswa berdasarkan total skor per jurusan.
                                                @break
                                            @case('statistik')
                                                Export ringkasan statistik keseluruhan proses seleksi.
                                                @break
                                        @endswitch
                                    </p>
                                    <button class="btn btn-primary btn-sm" onclick="exportData('{{ $key }}')">
                                        <i class="fas fa-download"></i> Export {{ $label }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Export</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle"></i> Catatan Penting:</h6>
                    <ul class="mb-0">
                        <li>Data yang diexport sesuai dengan tahun akademik aktif</li>
                        <li>File akan diunduh dalam format Excel (.xlsx)</li>
                        <li>Pastikan browser mengizinkan download file</li>
                        <li>Proses export mungkin membutuhkan waktu untuk data yang besar</li>
                    </ul>
                </div>

                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tahun Akademik</span>
                        <span class="info-box-number">{{ $tahunAkademik->nama_tahun }}</span>
                    </div>
                </div>

                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Waktu Export</span>
                        <span class="info-box-number">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Export</h3>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Fitur riwayat export akan segera tersedia</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
function exportData(type) {
    // Show loading
    Swal.fire({
        title: 'Memproses Export...',
        text: 'Mohon tunggu, sedang memproses data untuk export.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Build export URL
    let exportUrl = '';
    switch(type) {
        case 'siswa':
            exportUrl = '{{ route("admin.reports.export.siswa") }}';
            break;
        case 'hasil_seleksi':
            exportUrl = '{{ route("admin.reports.export.hasil-seleksi") }}';
            break;
        case 'ranking':
            exportUrl = '{{ route("admin.reports.export.ranking") }}';
            break;
        case 'statistik':
            exportUrl = '{{ route("admin.reports.export.statistik") }}';
            break;
        default:
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Tipe export tidak valid.',
                confirmButtonText: 'OK'
            });
            return;
    }

    // Create a temporary link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Close loading and show success
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Export Berhasil',
            text: 'File sedang diunduh. Periksa folder download Anda.',
            confirmButtonText: 'OK',
            timer: 3000,
            timerProgressBar: true
        });
    }, 1000);
}
</script>
@stop
