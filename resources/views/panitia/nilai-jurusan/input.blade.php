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

                    @foreach($masterKriteria as $kriteria)
                        <div class="form-group">
                            <label for="nilai_{{ $kriteria->id }}">
                                {{ $kriteria->nama_kriteria }}
                                <span class="text-danger">*</span>
                                <small class="text-muted">(Rentang: {{ $kriteria->nilai_min }} - {{ $kriteria->nilai_max }})</small>
                            </label>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control @error("nilai.{$kriteria->id}") is-invalid @enderror"
                                               id="nilai_{{ $kriteria->id }}"
                                               name="nilai[{{ $kriteria->id }}]"
                                               value="{{ old("nilai.{$kriteria->id}", isset($nilaiSiswa[$kriteria->id]) ? $nilaiSiswa[$kriteria->id]->nilai : '') }}"
                                               min="{{ $kriteria->nilai_min }}"
                                               max="{{ $kriteria->nilai_max }}"
                                               step="0.01"
                                               placeholder="{{ $kriteria->nilai_min }}-{{ $kriteria->nilai_max }}"
                                               required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fas fa-star"></i>
                                            </span>
                                        </div>
                                        @error("nilai.{$kriteria->id}")
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <input type="text"
                                           class="form-control @error("keterangan.{$kriteria->id}") is-invalid @enderror"
                                           name="keterangan[{{ $kriteria->id }}]"
                                           value="{{ old("keterangan.{$kriteria->id}", isset($nilaiSiswa[$kriteria->id]) ? $nilaiSiswa[$kriteria->id]->keterangan : '') }}"
                                           placeholder="Keterangan (opsional)">
                                    @error("keterangan.{$kriteria->id}")
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            @if($kriteria->deskripsi)
                                <small class="form-text text-muted">{{ $kriteria->deskripsi }}</small>
                            @endif
                        </div>
                    @endforeach

                    @if($masterKriteria->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Belum ada kriteria penilaian yang aktif untuk jurusan ini.
                            Hubungi administrator untuk menambahkan kriteria.
                        </div>
                    @endif
                </div>
                
                @if($masterKriteria->isNotEmpty())
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
