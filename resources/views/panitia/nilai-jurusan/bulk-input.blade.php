@extends('adminlte::page')

@section('title', 'Input Massal Nilai - ' . $jurusan->nama_jurusan)

@section('adminlte_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('content_header')
    <h1>Input Massal Nilai - {{ $jurusan->nama_jurusan }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-table"></i>
                    Input Nilai Massal
                </h3>
                <div class="card-tools">
                    <a href="{{ route('panitia.nilai-jurusan.siswa', $jurusan) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('panitia.nilai-jurusan.bulk.store', $jurusan) }}" method="POST" id="bulkForm">
                @csrf
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($masterKriteria->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Belum ada kriteria penilaian yang aktif untuk jurusan ini.
                        </div>
                    @elseif($siswa->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Belum ada siswa yang mendaftar di jurusan ini.
                        </div>
                    @else
                        <!-- Kriteria Info -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Kriteria Penilaian:</h6>
                            <div class="row">
                                @foreach($masterKriteria as $kriteria)
                                    <div class="col-md-3 mb-2">
                                        <span class="badge badge-primary">{{ $kriteria->nama_kriteria }}</span>
                                        <small class="text-muted d-block">Bobot: {{ $kriteria->bobot }}%</small>
                                        <small class="text-muted">Range: {{ $kriteria->nilai_min }} - {{ $kriteria->nilai_max }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bulk Input Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm" id="bulkTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th rowspan="2" class="align-middle" width="5%">No</th>
                                        <th rowspan="2" class="align-middle" width="15%">No. Pendaftaran</th>
                                        <th rowspan="2" class="align-middle" width="20%">Nama Siswa</th>
                                        <th colspan="{{ $masterKriteria->count() }}" class="text-center">Kriteria Penilaian</th>
                                    </tr>
                                    <tr>
                                        @foreach($masterKriteria as $kriteria)
                                            <th class="text-center" width="{{ 60 / $masterKriteria->count() }}%">
                                                {{ $kriteria->nama_kriteria }}
                                                <br><small class="text-muted">Bobot: {{ $kriteria->bobot }}%</small>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($siswa as $index => $s)
                                        <tr>
                                            <td class="align-middle text-center">{{ $index + 1 }}</td>
                                            <td class="align-middle">
                                                <strong>{{ $s->no_pendaftaran }}</strong>
                                                @if($s->kategori == 'khusus')
                                                    <br><span class="badge badge-warning badge-sm">Khusus</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <strong>{{ $s->nama_lengkap }}</strong>
                                                <br><small class="text-muted">{{ $s->nisn }}</small>
                                            </td>
                                            @foreach($masterKriteria as $kriteria)
                                                <td class="text-center">
                                                    <input type="number"
                                                           class="form-control form-control-sm text-center nilai-input"
                                                           name="nilai[{{ $s->id }}][{{ $kriteria->id }}]"
                                                           value="{{ isset($existingNilai[$s->id][$kriteria->id]) ? $existingNilai[$s->id][$kriteria->id] : '' }}"
                                                           min="{{ $kriteria->nilai_min }}"
                                                           max="{{ $kriteria->nilai_max }}"
                                                           step="0.01"
                                                           placeholder="{{ $kriteria->nilai_min }}-{{ $kriteria->nilai_max }}"
                                                           data-siswa="{{ $s->id }}"
                                                           data-kriteria="{{ $kriteria->id }}">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-info btn-sm" id="fillAllBtn">
                                        <i class="fas fa-fill"></i> Isi Semua dengan Nilai
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" id="clearAllBtn">
                                        <i class="fas fa-eraser"></i> Kosongkan Semua
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <span class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Kosongkan field untuk tidak mengubah nilai yang sudah ada
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
                
                @if($masterKriteria->isNotEmpty() && $siswa->isNotEmpty())
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Simpan Semua Nilai
                        </button>
                        <a href="{{ route('panitia.nilai-jurusan.siswa', $jurusan) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <div class="float-right">
                            <small class="text-muted">
                                <span id="filledCount">0</span> dari <span id="totalFields">{{ $siswa->count() * $masterKriteria->count() }}</span> field terisi
                            </small>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Fill All Modal -->
<div class="modal fade" id="fillAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Isi Semua dengan Nilai</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="fillValue">Nilai yang akan diisi ke semua field:</label>
                    <input type="number" class="form-control" id="fillValue" min="0" max="100" step="0.01" placeholder="Masukkan nilai">
                </div>
                <div class="form-group">
                    <label for="fillKriteria">Kriteria yang akan diisi:</label>
                    <select class="form-control" id="fillKriteria">
                        <option value="all">Semua Kriteria</option>
                        @foreach($masterKriteria as $kriteria)
                            <option value="{{ $kriteria->id }}">{{ $kriteria->nama_kriteria }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmFillBtn">Isi Sekarang</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .nilai-input {
            width: 80px;
        }
        .thead-dark th {
            border-color: #454d55;
        }
        .table-sm td {
            padding: 0.3rem;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Show success/error messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif
            // Count filled fields
            function updateFilledCount() {
                let filled = $('.nilai-input').filter(function() {
                    return $(this).val() !== '';
                }).length;
                $('#filledCount').text(filled);
            }

            // Initial count
            updateFilledCount();

            // Update count on input change
            $('.nilai-input').on('input', function() {
                let min = parseFloat($(this).attr('min'));
                let max = parseFloat($(this).attr('max'));
                let value = parseFloat($(this).val());
                let $input = $(this);

                // Remove previous validation classes
                $input.removeClass('is-invalid is-valid');

                if ($(this).val() !== '') {
                    if (isNaN(value) || value < min || value > max) {
                        $input.addClass('is-invalid');

                        // Show tooltip with error message
                        let errorMsg = '';
                        if (isNaN(value)) {
                            errorMsg = 'Nilai harus berupa angka';
                        } else if (value < min) {
                            errorMsg = `Nilai minimal ${min}`;
                        } else if (value > max) {
                            errorMsg = `Nilai maksimal ${max}`;
                        }

                        $input.attr('title', errorMsg);
                    } else {
                        $input.addClass('is-valid');
                        $input.removeAttr('title');
                    }
                } else {
                    $input.removeAttr('title');
                }

                updateFilledCount();
            });

            // Fill all button
            $('#fillAllBtn').click(function() {
                $('#fillAllModal').modal('show');
            });

            // Confirm fill all
            $('#confirmFillBtn').click(function() {
                let value = $('#fillValue').val();
                let kriteria = $('#fillKriteria').val();

                if (value === '') {
                    alert('Masukkan nilai terlebih dahulu');
                    return;
                }

                if (kriteria === 'all') {
                    $('.nilai-input').val(value);
                } else {
                    $(`.nilai-input[data-kriteria="${kriteria}"]`).val(value);
                }

                updateFilledCount();
                $('#fillAllModal').modal('hide');
                $('#fillValue').val('');
            });

            // Clear all button
            $('#clearAllBtn').click(function() {
                if (confirm('Yakin ingin mengosongkan semua field?')) {
                    $('.nilai-input').val('');
                    updateFilledCount();
                }
            });

            // Keyboard navigation
            $('.nilai-input').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === 'Tab') {
                    e.preventDefault();
                    let inputs = $('.nilai-input');
                    let currentIndex = inputs.index(this);
                    if (currentIndex < inputs.length - 1) {
                        inputs.eq(currentIndex + 1).focus();
                    }
                }
            });

            // Form submission validation
            $('#bulkForm').on('submit', function(e) {
                let filledCount = $('.nilai-input').filter(function() {
                    return $(this).val() !== '';
                }).length;

                if (filledCount === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Minimal isi satu field nilai sebelum menyimpan',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                // Validate numeric values
                let hasError = false;
                $('.nilai-input').each(function() {
                    let value = $(this).val();
                    let min = parseFloat($(this).attr('min'));
                    let max = parseFloat($(this).attr('max'));

                    if (value !== '' && (!$.isNumeric(value) || parseFloat(value) < min || parseFloat(value) > max)) {
                        $(this).addClass('is-invalid');
                        hasError = true;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (hasError) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Validasi!',
                        text: 'Terdapat nilai yang tidak valid. Periksa field yang ditandai merah.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                // Show loading state
                $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                // Show progress indicator
                Swal.fire({
                    title: 'Menyimpan Data...',
                    text: 'Mohon tunggu, sedang memproses data nilai siswa.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        });
    </script>
@stop
