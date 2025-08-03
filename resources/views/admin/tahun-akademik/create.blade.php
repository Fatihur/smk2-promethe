@extends('adminlte::page')

@section('title', 'Tambah Tahun Akademik')

@section('content_header')
    <h1>Tambah Tahun Akademik</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Tahun Akademik</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.tahun-akademik.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('admin.tahun-akademik.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun">Tahun Akademik <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('tahun') is-invalid @enderror" 
                                       id="tahun" 
                                       name="tahun" 
                                       value="{{ old('tahun') }}"
                                       placeholder="Contoh: 2024/2025"
                                       pattern="[0-9]{4}/[0-9]{4}"
                                       title="Format: YYYY/YYYY (contoh: 2024/2025)">
                                @error('tahun')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Format: YYYY/YYYY (contoh: 2024/2025)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="semester">Semester <span class="text-danger">*</span></label>
                                <select class="form-control @error('semester') is-invalid @enderror" 
                                        id="semester" 
                                        name="semester">
                                    <option value="">Pilih Semester</option>
                                    <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       id="tanggal_mulai" 
                                       name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai') }}">
                                @error('tanggal_mulai')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" 
                                       name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai') }}">
                                @error('tanggal_selesai')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        Aktifkan tahun akademik ini
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Jika dicentang, tahun akademik lain akan dinonaktifkan secara otomatis.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.tahun-akademik.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Auto-format tahun input
    $('#tahun').on('input', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        if (value.length >= 4) {
            value = value.substring(0, 4) + '/' + value.substring(4, 8);
        }
        $(this).val(value);
    });

    // Validate dates
    $('#tanggal_mulai, #tanggal_selesai').on('change', function() {
        let startDate = $('#tanggal_mulai').val();
        let endDate = $('#tanggal_selesai').val();

        if (startDate && endDate && new Date(startDate) >= new Date(endDate)) {
            alert('Tanggal selesai harus lebih besar dari tanggal mulai');
            $('#tanggal_selesai').val('');
        }
    });
});
</script>
@stop
