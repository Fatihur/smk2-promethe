@extends('adminlte::page')

@section('title', 'Detail Master Kriteria')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Master Kriteria</h1>
        <a href="{{ route('admin.master-kriteria.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Kriteria</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.master-kriteria.edit', $masterKriterium) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Kode Kriteria:</th>
                        <td><span class="badge badge-info">{{ $masterKriterium->kode_kriteria }}</span></td>
                    </tr>
                    <tr>
                        <th>Nama Kriteria:</th>
                        <td>{{ $masterKriterium->nama_kriteria }}</td>
                    </tr>
                    <tr>
                        <th>Tipe:</th>
                        <td>
                            @if($masterKriterium->tipe === 'benefit')
                                <span class="badge badge-success">Benefit (Semakin tinggi semakin baik)</span>
                            @else
                                <span class="badge badge-warning">Cost (Semakin rendah semakin baik)</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Deskripsi:</th>
                        <td>{{ $masterKriterium->deskripsi ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($masterKriterium->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat:</th>
                        <td>{{ $masterKriterium->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui:</th>
                        <td>{{ $masterKriterium->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Usage Statistics -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Penggunaan</h3>
            </div>
            <div class="card-body">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-globe"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Kriteria Global</span>
                        <span class="info-box-number">Semua Jurusan</span>
                    </div>
                </div>

                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Data Nilai Siswa</span>
                        <span class="info-box-number">{{ $nilaiSiswaCount }} Record</span>
                    </div>
                </div>

                @if($nilaiSiswaCount > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian!</strong> Kriteria ini tidak dapat dihapus karena sedang digunakan.
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Kriteria ini dapat dihapus karena belum digunakan.
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi</h3>
            </div>
            <div class="card-body">
                <div class="btn-group-vertical w-100">
                    <a href="{{ route('admin.master-kriteria.edit', $masterKriterium) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Kriteria
                    </a>
                    
                    <form action="{{ route('admin.master-kriteria.toggle-status', $masterKriterium) }}" 
                          method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="btn btn-{{ $masterKriterium->is_active ? 'secondary' : 'success' }} w-100">
                            <i class="fas fa-{{ $masterKriterium->is_active ? 'times' : 'check' }}"></i>
                            {{ $masterKriterium->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>

                    @if($nilaiSiswaCount == 0)
                        <form action="{{ route('admin.master-kriteria.destroy', $masterKriterium) }}"
                              method="POST" style="display: inline;"
                              onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Hapus Kriteria
                            </button>
                        </form>
                    @else
                        <div class="btn-group-vertical w-100">
                            <button type="button" class="btn btn-danger" disabled title="Tidak dapat dihapus karena sedang digunakan">
                                <i class="fas fa-trash"></i> Hapus Kriteria
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm"
                                    data-toggle="modal" data-target="#forceDeleteModal">
                                <i class="fas fa-exclamation-triangle"></i> Paksa Hapus
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kriteria sekarang bersifat global, tidak per jurusan -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Informasi Penggunaan</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Kriteria Global:</strong> Kriteria ini berlaku untuk semua jurusan dengan bobot <strong>{{ number_format($masterKriterium->bobot, 2) }}</strong>
            dan rentang nilai <strong>{{ $masterKriterium->nilai_min }} - {{ $masterKriterium->nilai_max }}</strong>.
        </div>

        @if($nilaiSiswaCount > 0)
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                Kriteria ini telah digunakan untuk menilai <strong>{{ $nilaiSiswaCount }}</strong> siswa.
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Kriteria ini belum digunakan untuk menilai siswa manapun.
            </div>
        @endif
    </div>
</div>

<!-- Force Delete Modal -->
@if($nilaiSiswaCount > 0)
<div class="modal fade" id="forceDeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle"></i> Konfirmasi Paksa Hapus
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h6><strong>PERINGATAN!</strong></h6>
                    <p>Anda akan menghapus kriteria <strong>{{ $masterKriterium->nama_kriteria }}</strong> beserta:</p>
                    <ul>
                        @if($nilaiSiswaCount > 0)
                            <li><strong>{{ $nilaiSiswaCount }} data nilai siswa</strong></li>
                        @endif
                    </ul>
                    <p class="mb-0"><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
                </div>

                <p>Ketik <code>HAPUS</code> untuk mengonfirmasi:</p>
                <input type="text" id="confirmText" class="form-control" placeholder="Ketik HAPUS">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('admin.master-kriteria.force-destroy', $masterKriterium) }}"
                      method="POST" style="display: inline;" id="forceDeleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                        <i class="fas fa-trash"></i> Paksa Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@stop

@section('css')
<style>
.btn-group-vertical .btn {
    margin-bottom: 5px;
}
.btn-group-vertical .btn:last-child {
    margin-bottom: 0;
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Enable delete button only when user types "HAPUS"
    $('#confirmText').on('input', function() {
        const confirmBtn = $('#confirmDeleteBtn');
        if ($(this).val().toUpperCase() === 'HAPUS') {
            confirmBtn.prop('disabled', false);
        } else {
            confirmBtn.prop('disabled', true);
        }
    });

    // Reset form when modal is closed
    $('#forceDeleteModal').on('hidden.bs.modal', function() {
        $('#confirmText').val('');
        $('#confirmDeleteBtn').prop('disabled', true);
    });

    // Final confirmation before submit
    $('#forceDeleteForm').on('submit', function(e) {
        if (!confirm('Apakah Anda benar-benar yakin ingin menghapus kriteria ini beserta semua data terkait?')) {
            e.preventDefault();
        }
    });
});
</script>
@stop
