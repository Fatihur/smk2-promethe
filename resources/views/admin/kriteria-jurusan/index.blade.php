@extends('adminlte::page')

@section('title', 'Kelola Kriteria Jurusan')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Kelola Kriteria - {{ $jurusan->nama_jurusan }}</h1>
        <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
<!-- Info Card -->
<div class="row">
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-clipboard-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Kriteria</span>
                <span class="info-box-number">{{ $kriteriaJurusan->total() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-chart-line"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Kriteria Aktif</span>
                <span class="info-box-number">{{ $kriteriaJurusan->where('is_active', true)->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-ruler"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rentang Nilai</span>
                <span class="info-box-number">0 - 100</span>
            </div>
        </div>
    </div>
</div>

<!-- Add Kriteria Form -->
@if($availableKriteria->count() > 0)
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Kriteria ke Jurusan</h3>
    </div>
    <form action="{{ route('admin.kriteria-jurusan.store', $jurusan) }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="master_kriteria_id">Pilih Kriteria <span class="text-danger">*</span></label>
                        <select class="form-control @error('master_kriteria_id') is-invalid @enderror" 
                                id="master_kriteria_id" 
                                name="master_kriteria_id" 
                                required>
                            <option value="">Pilih Kriteria</option>
                            @foreach($availableKriteria as $kriteria)
                                <option value="{{ $kriteria->id }}" {{ old('master_kriteria_id') == $kriteria->id ? 'selected' : '' }}>
                                    {{ $kriteria->kode_kriteria }} - {{ $kriteria->nama_kriteria }}
                                </option>
                            @endforeach
                        </select>
                        @error('master_kriteria_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nilai_min">Nilai Min <span class="text-danger">*</span></label>
                        <input type="number"
                               class="form-control @error('nilai_min') is-invalid @enderror"
                               id="nilai_min"
                               name="nilai_min"
                               value="{{ old('nilai_min', 0) }}"
                               min="0"
                               step="0.01"
                               placeholder="0.00"
                               required>
                        @error('nilai_min')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nilai_max">Nilai Max <span class="text-danger">*</span></label>
                        <input type="number"
                               class="form-control @error('nilai_max') is-invalid @enderror"
                               id="nilai_max"
                               name="nilai_max"
                               value="{{ old('nilai_max', 100) }}"
                               min="0"
                               step="0.01"
                               placeholder="100.00"
                               required>
                        @error('nilai_max')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kriteria
            </button>
            <div class="mt-2">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    <strong>Contoh penggunaan:</strong>
                </small>
                <ul class="small text-muted mt-1 mb-0">
                    <li><strong>Rentang nilai:</strong> TPA = 91-100 (siswa harus dapat nilai 91 sampai 100)</li>
                    <li><strong>Nilai tetap:</strong> Psikotes = 5 (siswa harus dapat nilai tepat 5)</li>
                    <li><strong>Nilai acak:</strong> Minat = 0-1 (siswa dapat nilai 0 atau 1 secara acak)</li>
                </ul>
            </div>
        </div>
    </form>
</div>
@endif

<!-- Kriteria List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kriteria Jurusan</h3>
        <div class="card-tools">
            <form method="GET" action="{{ route('admin.kriteria-jurusan.index', $jurusan) }}" class="form-inline">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari kriteria..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.kriteria-jurusan.index', $jurusan) }}" class="btn btn-default" title="Reset">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Kriteria</th>
                    <th>Tipe</th>
                    <th>Rentang Nilai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kriteriaJurusan as $index => $item)
                    <tr>
                        <td>{{ $kriteriaJurusan->firstItem() + $index }}</td>
                        <td><span class="badge badge-info">{{ $item->masterKriteria->kode_kriteria }}</span></td>
                        <td>{{ $item->masterKriteria->nama_kriteria }}</td>
                        <td>
                            @if($item->masterKriteria->tipe === 'benefit')
                                <span class="badge badge-success">Benefit</span>
                            @else
                                <span class="badge badge-warning">Cost</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $item->nilai_min }} - {{ $item->nilai_max }}</span>
                        </td>
                        <td>
                            @if($item->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#editModal{{ $item->id }}"
                                        title="Edit Rentang Nilai">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.kriteria-jurusan.toggle-status', [$jurusan, $item]) }}" 
                                      method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-{{ $item->is_active ? 'secondary' : 'success' }} btn-sm" 
                                            title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fas fa-{{ $item->is_active ? 'times' : 'check' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.kriteria-jurusan.destroy', [$jurusan, $item]) }}" 
                                      method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Yakin ingin menghapus kriteria ini dari jurusan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('admin.kriteria-jurusan.update', [$jurusan, $item]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Rentang Nilai Kriteria</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Kriteria</label>
                                            <input type="text" class="form-control"
                                                   value="{{ $item->masterKriteria->kode_kriteria }} - {{ $item->masterKriteria->nama_kriteria }}"
                                                   readonly>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nilai_min{{ $item->id }}">Nilai Min <span class="text-danger">*</span></label>
                                                    <input type="number"
                                                           class="form-control"
                                                           id="nilai_min{{ $item->id }}"
                                                           name="nilai_min"
                                                           value="{{ $item->nilai_min }}"
                                                           min="0"
                                                           step="0.01"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nilai_max{{ $item->id }}">Nilai Max <span class="text-danger">*</span></label>
                                                    <input type="number"
                                                           class="form-control"
                                                           id="nilai_max{{ $item->id }}"
                                                           name="nilai_max"
                                                           value="{{ $item->nilai_max }}"
                                                           min="0"
                                                           step="0.01"
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       class="custom-control-input"
                                                       id="is_active{{ $item->id }}"
                                                       name="is_active"
                                                       value="1"
                                                       {{ $item->is_active ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active{{ $item->id }}">Aktif</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada kriteria yang ditambahkan ke jurusan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($kriteriaJurusan->hasPages())
        <div class="card-footer">
            {{ $kriteriaJurusan->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@if($kriteriaJurusan->count() > 0)
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <strong>Info:</strong> Sistem menggunakan rentang nilai untuk setiap kriteria.
        Pastikan rentang nilai tidak bertumpang tindih antar kriteria.
    </div>
@endif




@stop

@section('css')
<style>
.btn-group .btn {
    margin-right: 2px;
}
.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@stop


