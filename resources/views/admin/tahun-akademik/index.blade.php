@extends('adminlte::page')

@section('title', 'Kelola Tahun Akademik')

@section('content_header')
    <h1>Kelola Tahun Akademik</h1>
@stop

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                {{ session('warning') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-info"></i> Informasi!</h5>
                {{ session('info') }}
            </div>
        @endif

        <!-- Search and Filter Card -->
        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-search"></i> Pencarian & Filter
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="display: none;">
                <form method="GET" action="{{ route('admin.tahun-akademik.index') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">Pencarian</label>
                                <input type="text" class="form-control" id="search" name="search"
                                       value="{{ request('search') }}" placeholder="Cari tahun akademik...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Semua</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sort_by">Urutkan</label>
                                <select class="form-control" id="sort_by" name="sort_by">
                                    <option value="tahun" {{ request('sort_by') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                                    <option value="tanggal_mulai" {{ request('sort_by') == 'tanggal_mulai' ? 'selected' : '' }}>Tanggal Mulai</option>
                                    <option value="is_active" {{ request('sort_by') == 'is_active' ? 'selected' : '' }}>Status</option>
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Dibuat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="sort_order">Arah</label>
                                <select class="form-control" id="sort_order" name="sort_order">
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>↓</option>
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>↑</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary btn-sm mr-1">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ route('admin.tahun-akademik.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-undo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Daftar Tahun Akademik
                    @if(request()->hasAny(['search', 'status', 'year_from', 'year_to']))
                        <small class="text-muted">({{ $tahunAkademik->total() }} hasil ditemukan)</small>
                    @endif
                </h3>
                <div class="card-tools">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-warning btn-sm" id="bulkDeactivateBtn" style="display: none;">
                            <i class="fas fa-times-circle"></i> Nonaktifkan Terpilih
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">
                            <i class="fas fa-trash"></i> Hapus Terpilih
                        </button>
                        <a href="{{ route('admin.tahun-akademik.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Tahun Akademik
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($tahunAkademik->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 30px">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="selectAll">
                                            <label class="custom-control-label" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th style="width: 10px">#</th>
                                    <th>Tahun Akademik</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Jumlah Siswa</th>
                                    <th style="width: 200px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tahunAkademik as $index => $ta)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input row-checkbox"
                                                       id="checkbox_{{ $ta->id }}" value="{{ $ta->id }}"
                                                       data-can-delete="{{ $ta->siswa_count == 0 && !$ta->is_active ? 'true' : 'false' }}"
                                                       data-is-active="{{ $ta->is_active ? 'true' : 'false' }}">
                                                <label class="custom-control-label" for="checkbox_{{ $ta->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $tahunAkademik->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $ta->tahun }}</strong>
                                            <small class="text-muted d-block">Semester Ganjil</small>
                                            @if($ta->is_active)
                                                <span class="badge badge-success ml-1">Aktif</span>
                                            @endif
                                        </td>
                                        <td>{{ $ta->tanggal_mulai->format('d/m/Y') }}</td>
                                        <td>{{ $ta->tanggal_selesai->format('d/m/Y') }}</td>
                                        <td>
                                            @if($ta->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $ta->siswa_count }} siswa</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.tahun-akademik.show', $ta) }}" 
                                                   class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.tahun-akademik.edit', $ta) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(!$ta->is_active)
                                                    <form action="{{ route('admin.tahun-akademik.set-active', $ta) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm" 
                                                                title="Aktifkan" 
                                                                onclick="return confirm('Yakin ingin mengaktifkan tahun akademik ini?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($ta->siswa_count == 0 && !$ta->is_active)
                                                    <form action="{{ route('admin.tahun-akademik.destroy', $ta) }}"
                                                          method="POST" style="display: inline;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm btn-delete"
                                                                title="Hapus"
                                                                data-tahun="{{ $ta->tahun }}"
                                                                data-message="Yakin ingin menghapus tahun akademik {{ $ta->tahun }}?">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @elseif($ta->siswa_count > 0)
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            title="Tidak dapat dihapus - masih ada {{ $ta->siswa_count }} siswa"
                                                            disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @elseif($ta->is_active)
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            title="Tidak dapat dihapus - tahun akademik aktif"
                                                            disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $tahunAkademik->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada tahun akademik</h5>
                        <p class="text-muted">Klik tombol "Tambah Tahun Akademik" untuk menambah data baru.</p>
                        <a href="{{ route('admin.tahun-akademik.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Tahun Akademik
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bulk Action Form -->
        <form id="bulkActionForm" action="{{ route('admin.tahun-akademik.bulk-action') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="action" id="bulkAction">
            <div id="selectedIds"></div>
        </form>
    </div>
@stop

@section('js')
<script>
// Simple confirm delete function
function confirmDelete(tahun) {
    const message = `Anda yakin ingin menghapus tahun akademik ${tahun}?\n\nPeringatan: Tindakan ini tidak dapat dibatalkan!`;
    return confirm(message);
}

$(document).ready(function() {
    // Bulk operations
    let selectedIds = [];

    // Select all checkbox
    $('#selectAll').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.row-checkbox').prop('checked', isChecked);
        updateSelectedIds();
        toggleBulkButtons();
    });

    // Individual row checkboxes
    $('.row-checkbox').on('change', function() {
        updateSelectedIds();
        toggleBulkButtons();

        // Update select all checkbox
        const totalCheckboxes = $('.row-checkbox').length;
        const checkedCheckboxes = $('.row-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Update selected IDs array
    function updateSelectedIds() {
        selectedIds = [];
        $('.row-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
    }

    // Toggle bulk action buttons
    function toggleBulkButtons() {
        if (selectedIds.length > 0) {
            $('#bulkDeleteBtn, #bulkDeactivateBtn').show();

            // Check if any selected items can be deleted
            let canDelete = false;
            let hasActive = false;

            $('.row-checkbox:checked').each(function() {
                if ($(this).data('can-delete') === true) {
                    canDelete = true;
                }
                if ($(this).data('is-active') === true) {
                    hasActive = true;
                }
            });

            $('#bulkDeleteBtn').toggle(canDelete);
            $('#bulkDeactivateBtn').toggle(hasActive);
        } else {
            $('#bulkDeleteBtn, #bulkDeactivateBtn').hide();
        }
    }

    // Bulk delete
    $('#bulkDeleteBtn').on('click', function() {
        if (selectedIds.length === 0) return;

        // Check if SweetAlert2 is available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Hapus Massal',
                html: `
                    <div class="text-left">
                        <p>Anda yakin ingin menghapus <strong>${selectedIds.length}</strong> tahun akademik yang dipilih?</p>
                        <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus Semua!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performBulkAction('delete');
                }
            });
        } else {
            // Fallback to native confirm dialog
            const message = `Anda yakin ingin menghapus ${selectedIds.length} tahun akademik yang dipilih?\n\nPeringatan: Tindakan ini tidak dapat dibatalkan!`;
            if (confirm(message)) {
                performBulkAction('delete');
            }
        }
    });

    // Bulk deactivate
    $('#bulkDeactivateBtn').on('click', function() {
        if (selectedIds.length === 0) return;

        // Check if SweetAlert2 is available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Nonaktifkan Massal',
                html: `
                    <div class="text-left">
                        <p>Anda yakin ingin menonaktifkan <strong>${selectedIds.length}</strong> tahun akademik yang dipilih?</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-times-circle"></i> Ya, Nonaktifkan!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performBulkAction('deactivate');
                }
            });
        } else {
            // Fallback to native confirm dialog
            const message = `Anda yakin ingin menonaktifkan ${selectedIds.length} tahun akademik yang dipilih?`;
            if (confirm(message)) {
                performBulkAction('deactivate');
            }
        }
    });

    // Perform bulk action
    function performBulkAction(action) {
        // Show loading if SweetAlert2 is available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Prepare form
        $('#bulkAction').val(action);
        $('#selectedIds').empty();
        selectedIds.forEach(function(id) {
            $('#selectedIds').append(`<input type="hidden" name="selected_ids[]" value="${id}">`);
        });

        // Submit form
        $('#bulkActionForm').submit();
    }

    // Alternative delete confirmation using SweetAlert2 if available
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();

        const tahun = $(this).data('tahun');
        const form = $(this).closest('.delete-form');

        // Check if SweetAlert2 is available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `
                    <div class="text-left">
                        <p>Anda yakin ingin menghapus tahun akademik berikut?</p>
                        <div class="alert alert-warning">
                            <strong>Tahun Akademik:</strong> ${tahun} (Semester Ganjil)
                        </div>
                        <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form
                    form.submit();
                }
            });
        } else {
            // Fallback to native confirm dialog
            const message = `Anda yakin ingin menghapus tahun akademik ${tahun}?\n\nPeringatan: Tindakan ini tidak dapat dibatalkan!`;
            if (confirm(message)) {
                form.submit();
            }
        }
    });

    // Auto-submit filter form on change
    $('#filterForm select').on('change', function() {
        $('#filterForm').submit();
    });

    // Simple delete confirmation for individual items (fallback)
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const message = $(this).data('message') || 'Yakin ingin menghapus data ini?';

        if (confirm(message)) {
            form.submit();
        }
    });

    // Initialize tooltips
    $('[title]').tooltip();

    // Debug log
    console.log('Tahun Akademik index page loaded');
    console.log('Delete buttons found:', $('.btn-delete').length);
});
</script>
@stop
