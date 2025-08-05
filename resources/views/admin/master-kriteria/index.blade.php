@extends('adminlte::page')

@section('title', 'Master Kriteria')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Master Kriteria</h1>
        <a href="{{ route('admin.master-kriteria.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kriteria
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Master Kriteria</h3>
        <div class="card-tools">
            <form method="GET" action="{{ route('admin.master-kriteria.index') }}" class="form-inline">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari kriteria..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.master-kriteria.index') }}" class="btn btn-default" title="Reset">
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
                    <th>Bobot</th>
                    <th>Rentang Nilai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($masterKriteria as $index => $item)
                    <tr>
                        <td>{{ $masterKriteria->firstItem() + $index }}</td>
                        <td><span class="badge badge-info">{{ $item->kode_kriteria }}</span></td>
                        <td>{{ $item->nama_kriteria }}</td>
                        <td>
                            @if($item->tipe === 'benefit')
                                <span class="badge badge-success">Benefit</span>
                            @else
                                <span class="badge badge-warning">Cost</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ number_format($item->bobot, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ $item->nilai_min }} - {{ $item->nilai_max }}</span>
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
                                <a href="{{ route('admin.master-kriteria.show', $item) }}" 
                                   class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.master-kriteria.edit', $item) }}" 
                                   class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.master-kriteria.toggle-status', $item) }}" 
                                      method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-{{ $item->is_active ? 'secondary' : 'success' }} btn-sm" 
                                            title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fas fa-{{ $item->is_active ? 'times' : 'check' }}"></i>
                                    </button>
                                </form>
                                @if($item->nilaiSiswa()->count() == 0)
                                    <form action="{{ route('admin.master-kriteria.destroy', $item) }}"
                                          method="POST" style="display: inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-danger btn-sm" disabled
                                            title="Tidak dapat dihapus karena sedang digunakan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data kriteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($masterKriteria->hasPages())
        <div class="card-footer">
            {{ $masterKriteria->appends(request()->query())->links() }}
        </div>
    @endif
</div>
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
