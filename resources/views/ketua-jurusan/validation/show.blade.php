@extends('adminlte::page')

@section('title', 'Validasi Hasil Seleksi')

@section('content_header')
    <h1>Validasi Hasil Seleksi</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-check text-primary"></i>
                    Detail Siswa untuk Validasi
                </h3>
                <div class="card-tools">
                    <a href="{{ route('ketua-jurusan.validation.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Informasi Siswa -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Informasi Siswa</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>No. Pendaftaran:</strong></td>
                                <td>{{ $result->siswa->no_pendaftaran }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap:</strong></td>
                                <td>{{ $result->siswa->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori:</strong></td>
                                <td>
                                    @if($result->kategori == 'khusus')
                                        <span class="badge badge-warning">Khusus</span>
                                    @else
                                        <span class="badge badge-info">Umum</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Pilihan Jurusan 1:</strong></td>
                                <td>{{ $result->siswa->pilihanJurusan1->nama_jurusan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pilihan Jurusan 2:</strong></td>
                                <td>{{ $result->siswa->pilihanJurusan2->nama_jurusan ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Hasil PROMETHEE</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Ranking:</strong></td>
                                <td><span class="badge badge-primary">#{{ $result->ranking }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Phi Plus:</strong></td>
                                <td>{{ number_format($result->phi_plus, 4) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phi Minus:</strong></td>
                                <td>{{ number_format($result->phi_minus, 4) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phi Net:</strong></td>
                                <td><strong>{{ number_format($result->phi_net, 4) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Status Kuota:</strong></td>
                                <td>
                                    @if($result->masuk_kuota)
                                        <span class="badge badge-success">Dalam Kuota</span>
                                    @else
                                        <span class="badge badge-secondary">Luar Kuota</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Nilai Siswa -->
                @if($result->siswa->nilaiSiswa->isNotEmpty())
                <div class="mb-4">
                    <h5>Nilai Siswa</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result->siswa->nilaiSiswa as $nilai)
                                    <tr>
                                        <td>{{ $nilai->masterKriteria->nama_kriteria ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $nilai->nilai }}</span>
                                        </td>
                                        <td>{{ $nilai->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-check text-success"></i>
                    Form Validasi
                </h3>
            </div>
            <form action="{{ route('ketua-jurusan.validation.validate', $result) }}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="status_validasi">Status Validasi <span class="text-danger">*</span></label>
                        <select name="status_validasi" id="status_validasi" class="form-control @error('status_validasi') is-invalid @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="lulus" {{ old('status_validasi') == 'lulus' ? 'selected' : '' }}>
                                Lulus (Pilihan 1)
                            </option>
                            @if($result->siswa->pilihan_jurusan_2)
                                <option value="lulus_pilihan_2" {{ old('status_validasi') == 'lulus_pilihan_2' ? 'selected' : '' }}>
                                    Lulus (Pilihan 2)
                                </option>
                            @endif
                            <option value="tidak_lulus" {{ old('status_validasi') == 'tidak_lulus' ? 'selected' : '' }}>
                                Tidak Lulus
                            </option>
                        </select>
                        @error('status_validasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="catatan_validasi">Catatan Validasi</label>
                        <textarea name="catatan_validasi" id="catatan_validasi" 
                                  class="form-control @error('catatan_validasi') is-invalid @enderror" 
                                  rows="4" placeholder="Masukkan catatan validasi (opsional)">{{ old('catatan_validasi') }}</textarea>
                        @error('catatan_validasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Maksimal 500 karakter</small>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="icon fas fa-info"></i> Informasi Validasi</h6>
                        <ul class="mb-0">
                            <li><strong>Lulus (Pilihan 1):</strong> Siswa diterima di jurusan pilihan pertama</li>
                            @if($result->siswa->pilihan_jurusan_2)
                                <li><strong>Lulus (Pilihan 2):</strong> Siswa diterima di jurusan pilihan kedua</li>
                            @endif
                            <li><strong>Tidak Lulus:</strong> Siswa tidak diterima di jurusan manapun</li>
                        </ul>
                        <hr>
                        <small class="text-muted">
                            <strong>Catatan:</strong> Anda hanya dapat memvalidasi siswa yang memilih jurusan Anda sebagai pilihan pertama.
                            @if($result->kategori == 'khusus')
                                Untuk kategori khusus, hanya siswa yang lolos perangkingan yang dapat divalidasi.
                            @endif
                        </small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success" 
                            onclick="return confirm('Yakin ingin menyimpan validasi ini?')">
                        <i class="fas fa-save"></i> Simpan Validasi
                    </button>
                    <a href="{{ route('ketua-jurusan.validation.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .table-borderless td {
            border: none;
            padding: 0.25rem 0.75rem;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-resize textarea
            $('textarea').on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    </script>
@stop
