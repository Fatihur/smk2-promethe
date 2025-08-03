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

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Tahun Akademik</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.tahun-akademik.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Tahun Akademik
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($tahunAkademik->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Tahun</th>
                                    <th>Semester</th>
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
                                        <td>{{ $tahunAkademik->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $ta->tahun }}</strong>
                                            @if($ta->is_active)
                                                <span class="badge badge-success ml-1">Aktif</span>
                                            @endif
                                        </td>
                                        <td>{{ $ta->semester }}</td>
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
                                            <span class="badge badge-info">{{ $ta->siswa->count() }} siswa</span>
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
                                                @if($ta->siswa->count() == 0)
                                                    <form action="{{ route('admin.tahun-akademik.destroy', $ta) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                title="Hapus"
                                                                onclick="return confirm('Yakin ingin menghapus tahun akademik ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
    </div>
@stop
