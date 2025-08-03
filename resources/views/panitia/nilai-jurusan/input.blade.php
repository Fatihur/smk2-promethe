@extends('adminlte::page')

@section('title', 'Input Nilai - ' . $siswa->nama_lengkap)

@section('content_header')
    <h1>Input Nilai Siswa</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Siswa</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>No. Pendaftaran</th>
                        <td>: {{ $siswa->no_pendaftaran }}</td>
                    </tr>
                    <tr>
                        <th>NISN</th>
                        <td>: {{ $siswa->nisn }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>: {{ $siswa->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>Asal Sekolah</th>
                        <td>: {{ $siswa->asal_sekolah }}</td>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <td>: {{ $jurusan->nama_jurusan }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>: 
                            @if($siswa->kategori == 'khusus')
                                <span class="badge badge-warning">Khusus</span>
                            @else
                                <span class="badge badge-info">Umum</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Input Nilai</h3>
                <div class="card-tools">
                    <a href="{{ route('panitia.nilai-jurusan.siswa', $jurusan) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('panitia.nilai-jurusan.store', [$jurusan, $siswa]) }}" method="POST">
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

                    @foreach($kriteriaJurusan as $kj)
                        <div class="form-group">
                            <label for="nilai_{{ $kj->master_kriteria_id }}">
                                {{ $kj->masterKriteria->nama_kriteria }}
                                <span class="text-danger">*</span>
                                <small class="text-muted">(Rentang: {{ $kj->nilai_min }} - {{ $kj->nilai_max }})</small>
                            </label>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error("nilai.{$kj->master_kriteria_id}") is-invalid @enderror" 
                                               id="nilai_{{ $kj->master_kriteria_id }}" 
                                               name="nilai[{{ $kj->master_kriteria_id }}]" 
                                               value="{{ old("nilai.{$kj->master_kriteria_id}", isset($nilaiSiswa[$kj->master_kriteria_id]) ? $nilaiSiswa[$kj->master_kriteria_id]->nilai : '') }}" 
                                               min="{{ $kj->nilai_min }}" 
                                               max="{{ $kj->nilai_max }}" 
                                               step="0.01" 
                                               placeholder="{{ $kj->nilai_min }}-{{ $kj->nilai_max }}" 
                                               required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fas fa-star"></i>
                                            </span>
                                        </div>
                                        @error("nilai.{$kj->master_kriteria_id}")
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" 
                                           class="form-control @error("keterangan.{$kj->master_kriteria_id}") is-invalid @enderror" 
                                           name="keterangan[{{ $kj->master_kriteria_id }}]" 
                                           value="{{ old("keterangan.{$kj->master_kriteria_id}", isset($nilaiSiswa[$kj->master_kriteria_id]) ? $nilaiSiswa[$kj->master_kriteria_id]->keterangan : '') }}" 
                                           placeholder="Keterangan (opsional)">
                                    @error("keterangan.{$kj->master_kriteria_id}")
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            @if($kj->masterKriteria->deskripsi)
                                <small class="form-text text-muted">{{ $kj->masterKriteria->deskripsi }}</small>
                            @endif
                        </div>
                    @endforeach

                    @if($kriteriaJurusan->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Belum ada kriteria penilaian yang aktif untuk jurusan ini. 
                            Hubungi administrator untuk menambahkan kriteria.
                        </div>
                    @endif
                </div>
                
                @if($kriteriaJurusan->isNotEmpty())
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Nilai
                        </button>
                        <a href="{{ route('panitia.nilai-jurusan.siswa', $jurusan) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .form-group label {
            font-weight: 600;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-focus first input
            $('input[type="number"]').first().focus();
            
            // Add input validation
            $('input[type="number"]').on('input', function() {
                let min = parseFloat($(this).attr('min'));
                let max = parseFloat($(this).attr('max'));
                let value = parseFloat($(this).val());
                
                if (value < min) {
                    $(this).val(min);
                } else if (value > max) {
                    $(this).val(max);
                }
            });

            // Add keyboard navigation
            $('input[type="number"]').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    let inputs = $('input[type="number"]');
                    let currentIndex = inputs.index(this);
                    if (currentIndex < inputs.length - 1) {
                        inputs.eq(currentIndex + 1).focus();
                    } else {
                        $('button[type="submit"]').focus();
                    }
                }
            });
        });
    </script>
@stop
