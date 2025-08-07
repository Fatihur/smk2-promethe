@extends('adminlte::page')

@section('title', 'Edit Tahun Akademik')

@section('content_header')
    <h1>Edit Tahun Akademik</h1>
@stop

@section('content')
    <div class="container-fluid">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Tahun Akademik</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.tahun-akademik.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('admin.tahun-akademik.update', $tahun_akademik) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun">Tahun Akademik <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('tahun') is-invalid @enderror" 
                                       id="tahun" 
                                       name="tahun" 
                                       value="{{ old('tahun', $tahun_akademik->tahun) }}"
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
                                <label for="semester_display">Semester</label>
                                <input type="text"
                                       class="form-control"
                                       id="semester_display"
                                       value="Ganjil"
                                       readonly>
                                <small class="form-text text-muted">Semester otomatis diatur ke Ganjil</small>
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
                                       value="{{ old('tanggal_mulai', $tahun_akademik->tanggal_mulai->format('Y-m-d')) }}">
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
                                       value="{{ old('tanggal_selesai', $tahun_akademik->tanggal_selesai->format('Y-m-d')) }}">
                                @error('tanggal_selesai')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- Hidden input to ensure is_active is always sent -->
                                <input type="hidden" name="is_active" value="0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $tahun_akademik->is_active) ? 'checked' : '' }}>
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

                    @if($tahun_akademik->siswa->count() > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Informasi:</strong> Tahun akademik ini memiliki {{ $tahun_akademik->siswa->count() }} data siswa.
                            Perubahan tanggal dapat mempengaruhi data yang sudah ada.
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
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

        // Validate year format and sequence
        if (value.length === 9) {
            validateYearFormat(value);
        }
    });

    // Validate year format and sequence
    function validateYearFormat(value) {
        const yearPattern = /^\d{4}\/\d{4}$/;
        const $tahunInput = $('#tahun');

        if (!yearPattern.test(value)) {
            showFieldError($tahunInput, 'Format harus YYYY/YYYY (contoh: 2024/2025)');
            return false;
        }

        const years = value.split('/');
        const startYear = parseInt(years[0]);
        const endYear = parseInt(years[1]);

        if (endYear !== startYear + 1) {
            showFieldError($tahunInput, 'Tahun harus berurutan (contoh: 2024/2025)');
            return false;
        }

        if (startYear < 2020 || startYear > 2050) {
            showFieldError($tahunInput, 'Tahun harus dalam rentang 2020-2050');
            return false;
        }

        clearFieldError($tahunInput);
        return true;
    }

    // Show field error
    function showFieldError($field, message) {
        $field.addClass('is-invalid');
        $field.siblings('.invalid-feedback').remove();
        $field.after('<div class="invalid-feedback">' + message + '</div>');
    }

    // Clear field error
    function clearFieldError($field) {
        $field.removeClass('is-invalid');
        $field.siblings('.invalid-feedback').remove();
    }

    // Validate dates
    $('#tanggal_mulai, #tanggal_selesai').on('change', function() {
        validateDates();
    });

    function validateDates() {
        const startDate = $('#tanggal_mulai').val();
        const endDate = $('#tanggal_selesai').val();
        const $endDateInput = $('#tanggal_selesai');

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);

            if (start >= end) {
                showFieldError($endDateInput, 'Tanggal selesai harus setelah tanggal mulai');
                return false;
            }

            // Check if duration is reasonable (between 6 months and 2 years)
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays < 180) {
                showFieldError($endDateInput, 'Durasi tahun akademik minimal 6 bulan');
                return false;
            }

            if (diffDays > 730) {
                showFieldError($endDateInput, 'Durasi tahun akademik maksimal 2 tahun');
                return false;
            }

            clearFieldError($endDateInput);
        }

        return true;
    }

    // Form submission validation
    $('form').on('submit', function(e) {
        let isValid = true;

        // Validate tahun
        const tahunValue = $('#tahun').val();
        if (tahunValue && !validateYearFormat(tahunValue)) {
            isValid = false;
        }

        // Validate dates
        if (!validateDates()) {
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            toastr.error('Mohon perbaiki kesalahan pada form sebelum menyimpan.');
        }
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@stop
