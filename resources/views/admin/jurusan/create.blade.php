@extends('adminlte::page')

@section('title', 'Tambah Jurusan')

@section('content_header')
    <h1>Tambah Jurusan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Jurusan</h3>
        <div class="card-tools">
            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <form action="{{ route('admin.jurusan.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kode_jurusan">Kode Jurusan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_jurusan') is-invalid @enderror" 
                               id="kode_jurusan" name="kode_jurusan" value="{{ old('kode_jurusan') }}" 
                               placeholder="Contoh: TKJ" maxlength="10" required>
                        @error('kode_jurusan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Maksimal 10 karakter, akan diubah ke huruf besar</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_jurusan">Nama Jurusan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_jurusan') is-invalid @enderror" 
                               id="nama_jurusan" name="nama_jurusan" value="{{ old('nama_jurusan') }}" 
                               placeholder="Contoh: Teknik Komputer dan Jaringan" required>
                        @error('nama_jurusan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                          id="deskripsi" name="deskripsi" rows="3" 
                          placeholder="Deskripsi singkat tentang jurusan">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kuota">Kuota Siswa <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('kuota') is-invalid @enderror"
                               id="kuota" name="kuota" value="{{ old('kuota') }}"
                               placeholder="36" min="1" required>
                        @error('kuota')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Jumlah siswa yang dapat diterima</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kategori">Kategori Jurusan <span class="text-danger">*</span></label>
                        <select class="form-control @error('kategori') is-invalid @enderror"
                                id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoriOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('kategori') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Pilih kategori jurusan (Umum atau Khusus)</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   {{ old('is_active') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Jurusan Aktif
                            </label>
                        </div>
                        <small class="form-text text-muted">Centang jika jurusan aktif menerima siswa</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script>
        // Auto uppercase kode jurusan
        $('#kode_jurusan').on('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>
@stop
