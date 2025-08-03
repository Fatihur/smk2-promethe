@extends('adminlte::page')

@section('title', 'Proses PROMETHEE Khusus')

@section('content_header')
    <h1>Proses PROMETHEE Kategori Khusus</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star text-warning"></i>
                    Form Proses PROMETHEE Khusus (TAB/TSM)
                </h3>
                <div class="card-tools">
                    <a href="{{ route('panitia.promethee.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('panitia.promethee.khusus.run') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Informasi Proses</h5>
                        <p>Proses PROMETHEE untuk kategori khusus akan:</p>
                        <ul>
                            <li>Menghitung ranking untuk <strong>{{ $khususCount }} siswa</strong> kategori khusus</li>
                            <li>Menggunakan metode PROMETHEE dengan preference function "usual"</li>
                            <li>Menentukan siswa yang masuk kuota berdasarkan ranking</li>
                            <li>Siswa dalam kuota akan masuk tahap validasi ketua jurusan</li>
                            <li>Siswa di luar kuota akan otomatis dipindah ke kategori umum</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="kuota">Kuota Kategori Khusus <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('kuota') is-invalid @enderror" 
                               id="kuota" 
                               name="kuota" 
                               value="{{ old('kuota', 80) }}" 
                               min="1" 
                               max="{{ $khususCount }}" 
                               required>
                        @error('kuota')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Jumlah siswa yang akan masuk tahap validasi (maksimal {{ $khususCount }} siswa)
                        </small>
                    </div>

                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                        <p>Pastikan semua siswa kategori khusus sudah memiliki nilai lengkap sebelum menjalankan proses ini.</p>
                        <p>Proses ini akan menghapus hasil PROMETHEE sebelumnya untuk kategori khusus.</p>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning" 
                            onclick="return confirm('Yakin ingin menjalankan proses PROMETHEE untuk kategori khusus?')">
                        <i class="fas fa-play"></i> Jalankan PROMETHEE Khusus
                    </button>
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
                <h3 class="card-title">Statistik Kategori Khusus</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="description-block">
                            <h5 class="description-header">{{ $khususCount }}</h5>
                            <span class="description-text">Total Pendaftar Khusus</span>
                        </div>
                    </div>
                </div>

                <hr>

                <h6>Jurusan Kategori Khusus:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-cog text-warning"></i> Teknik Alat Berat (TAB)</li>
                    <li><i class="fas fa-motorcycle text-warning"></i> Teknik Sepeda Motor (TSM)</li>
                </ul>

                <hr>

                <h6>Tahapan Proses:</h6>
                <ol class="text-sm">
                    <li>Perhitungan PROMETHEE</li>
                    <li>Penentuan ranking</li>
                    <li>Seleksi berdasarkan kuota</li>
                    <li>Validasi ketua jurusan</li>
                    <li>Transfer siswa gagal ke umum</li>
                </ol>
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
    <script>
        $(document).ready(function() {
            // Auto-calculate recommended kuota (80% of total)
            let totalSiswa = {{ $khususCount }};
            let recommendedKuota = Math.floor(totalSiswa * 0.8);
            
            if (recommendedKuota > 0 && $('#kuota').val() == 80) {
                $('#kuota').val(recommendedKuota);
            }
        });
    </script>
@stop
