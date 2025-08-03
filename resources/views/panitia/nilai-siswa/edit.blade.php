@extends('adminlte::page')

@section('title', 'Input Nilai Siswa')

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
                        <th>Pilihan 1</th>
                        <td>: {{ $siswa->pilihanJurusan1->nama_jurusan }}</td>
                    </tr>
                    <tr>
                        <th>Pilihan 2</th>
                        <td>: {{ $siswa->pilihanJurusan2->nama_jurusan ?? '-' }}</td>
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
                    <a href="{{ route('panitia.siswa.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('panitia.nilai-siswa.update', $siswa) }}" method="POST">
                @csrf
                @method('PUT')
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

                    @foreach($kriteria as $k)
                        <div class="form-group">
                            <label for="nilai_{{ $k->id }}">
                                {{ $k->nama_kriteria }}
                                <span class="text-danger">*</span>
                                <small class="text-muted">(Rentang: {{ $k->nilai_min ?? 0 }} - {{ $k->nilai_max ?? 100 }})</small>
                                <span class="badge badge-secondary badge-sm ml-1">Global</span>
                            </label>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control @error("nilai.{$k->id}") is-invalid @enderror"
                                               id="nilai_{{ $k->id }}"
                                               name="nilai[{{ $k->id }}]"
                                               value="{{ old("nilai.{$k->id}", isset($nilaiSiswa[$k->id]) ? $nilaiSiswa[$k->id]->nilai : '') }}"
                                               min="{{ $k->nilai_min ?? 0 }}" max="{{ $k->nilai_max ?? 100 }}" step="0.01"
                                               placeholder="{{ $k->nilai_min ?? 0 }}-{{ $k->nilai_max ?? 100 }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fas fa-star"></i>
                                            </span>
                                        </div>
                                        @error("nilai.{$k->id}")
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <input type="text"
                                           class="form-control @error("keterangan.{$k->id}") is-invalid @enderror"
                                           name="keterangan[{{ $k->id }}]"
                                           value="{{ old("keterangan.{$k->id}", isset($nilaiSiswa[$k->id]) ? $nilaiSiswa[$k->id]->keterangan : '') }}"
                                           placeholder="Keterangan (opsional)">
                                    @error("keterangan.{$k->id}")
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            @if($k->deskripsi)
                                <small class="form-text text-muted">{{ $k->deskripsi }}</small>
                            @endif
                        </div>
                    @endforeach

                    @if($kriteria->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Belum ada kriteria penilaian yang aktif. Hubungi administrator untuk menambahkan kriteria.
                        </div>
                    @endif
                </div>
                
                @if($kriteria->isNotEmpty())
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Nilai
                        </button>
                        <a href="{{ route('panitia.siswa.index') }}" class="btn btn-secondary">
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
                let value = parseFloat($(this).val());
                if (value < 0) {
                    $(this).val(0);
                } else if (value > 100) {
                    $(this).val(100);
                }
            });
        });
    </script>
@stop
