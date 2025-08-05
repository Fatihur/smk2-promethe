@extends('adminlte::page')

@section('title', 'Edit Master Kriteria')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Edit Master Kriteria</h1>
        <a href="{{ route('admin.master-kriteria.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Edit Master Kriteria</h3>
    </div>

    <form action="{{ route('admin.master-kriteria.update', $masterKriterium) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kode_kriteria">Kode Kriteria <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('kode_kriteria') is-invalid @enderror" 
                               id="kode_kriteria" 
                               name="kode_kriteria" 
                               value="{{ old('kode_kriteria', $masterKriterium->kode_kriteria) }}" 
                               placeholder="Contoh: K01" 
                               maxlength="10" 
                               required>
                        @error('kode_kriteria')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tipe">Tipe Kriteria <span class="text-danger">*</span></label>
                        <select class="form-control @error('tipe') is-invalid @enderror" 
                                id="tipe" 
                                name="tipe" 
                                required>
                            <option value="">Pilih Tipe</option>
                            <option value="benefit" {{ old('tipe', $masterKriterium->tipe) == 'benefit' ? 'selected' : '' }}>
                                Benefit (Semakin tinggi semakin baik)
                            </option>
                            <option value="cost" {{ old('tipe', $masterKriterium->tipe) == 'cost' ? 'selected' : '' }}>
                                Cost (Semakin rendah semakin baik)
                            </option>
                        </select>
                        @error('tipe')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bobot dan Rentang Nilai -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bobot">Bobot <span class="text-danger">*</span></label>
                        <input type="number"
                               class="form-control @error('bobot') is-invalid @enderror"
                               id="bobot"
                               name="bobot"
                               value="{{ old('bobot', $masterKriterium->bobot) }}"
                               min="0.01"
                               max="100"
                               step="0.01"
                               placeholder="1.0"
                               required>
                        <small class="form-text text-muted">Bobot kriteria untuk perhitungan PROMETHEE</small>
                        @error('bobot')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nilai_min">Nilai Minimum <span class="text-danger">*</span></label>
                        <input type="number"
                               class="form-control @error('nilai_min') is-invalid @enderror"
                               id="nilai_min"
                               name="nilai_min"
                               value="{{ old('nilai_min', $masterKriterium->nilai_min) }}"
                               min="0"
                               step="0.01"
                               placeholder="0"
                               required>
                        @error('nilai_min')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nilai_max">Nilai Maksimum <span class="text-danger">*</span></label>
                        <input type="number"
                               class="form-control @error('nilai_max') is-invalid @enderror"
                               id="nilai_max"
                               name="nilai_max"
                               value="{{ old('nilai_max', $masterKriterium->nilai_max) }}"
                               min="1"
                               step="0.01"
                               placeholder="100"
                               required>
                        @error('nilai_max')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="nama_kriteria">Nama Kriteria <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error('nama_kriteria') is-invalid @enderror" 
                       id="nama_kriteria" 
                       name="nama_kriteria" 
                       value="{{ old('nama_kriteria', $masterKriterium->nama_kriteria) }}" 
                       placeholder="Masukkan nama kriteria" 
                       required>
                @error('nama_kriteria')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                          id="deskripsi" 
                          name="deskripsi" 
                          rows="3" 
                          placeholder="Masukkan deskripsi kriteria (opsional)">{{ old('deskripsi', $masterKriterium->deskripsi) }}</textarea>
                @error('deskripsi')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" 
                           class="custom-control-input" 
                           id="is_active" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', $masterKriterium->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">
                        Aktif
                    </label>
                </div>
                <small class="form-text text-muted">
                    Kriteria aktif dapat digunakan oleh jurusan.
                </small>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.master-kriteria.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>
@stop

@section('css')
<style>
.form-group label {
    font-weight: 600;
}
</style>
@stop
