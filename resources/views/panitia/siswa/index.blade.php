@extends('adminlte::page')

@section('title', 'Kelola Data Siswa')

@section('content_header')
    <h1>Kelola Data Siswa</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Calon Siswa</h3>
        <div class="card-tools">
            <a href="{{ route('panitia.siswa.export') }}" class="btn btn-info mr-2">
                <i class="fas fa-download"></i> Export Excel
            </a>
            <a href="{{ route('panitia.siswa.import') }}" class="btn btn-success mr-2">
                <i class="fas fa-upload"></i> Import Excel
            </a>
            <a href="{{ route('panitia.siswa.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Siswa
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter Form -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="kategori" class="form-control">
                        <option value="">Semua Kategori</option>
                        <option value="khusus" {{ request('kategori') == 'khusus' ? 'selected' : '' }}>Khusus</option>
                        <option value="umum" {{ request('kategori') == 'umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="tidak_lulus" {{ request('status') == 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                        <option value="lulus_pilihan_2" {{ request('status') == 'lulus_pilihan_2' ? 'selected' : '' }}>Lulus Pilihan 2</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="tahun_akademik_id" class="form-control">
                        @foreach($tahunAkademik as $tahun)
                            <option value="{{ $tahun->id }}" {{ request('tahun_akademik_id', $tahun->is_active ? $tahun->id : '') == $tahun->id ? 'selected' : '' }}>
                                {{ $tahun->tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('panitia.siswa.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="siswaTable">
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Pilihan 1</th>
                        <th>Pilihan 2</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $item)
                        <tr>
                            <td><strong>{{ $item->no_pendaftaran }}</strong></td>
                            <td>{{ $item->nisn }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $item->pilihanJurusan1->kode_jurusan }}</span>
                                {{ $item->pilihanJurusan1->nama_jurusan }}
                            </td>
                            <td>
                                @if($item->pilihanJurusan2)
                                    <span class="badge badge-secondary">{{ $item->pilihanJurusan2->kode_jurusan }}</span>
                                    {{ $item->pilihanJurusan2->nama_jurusan }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->kategori == 'khusus')
                                    <span class="badge badge-warning">Khusus</span>
                                @else
                                    <span class="badge badge-info">Umum</span>
                                @endif
                            </td>
                            <td>
                                @switch($item->status_seleksi)
                                    @case('pending')
                                        <span class="badge badge-secondary">Pending</span>
                                        @break
                                    @case('lulus')
                                        <span class="badge badge-success">Lulus</span>
                                        @break
                                    @case('tidak_lulus')
                                        <span class="badge badge-danger">Tidak Lulus</span>
                                        @break
                                    @case('lulus_pilihan_2')
                                        <span class="badge badge-warning">Lulus Pilihan 2</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('panitia.siswa.show', $item) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('panitia.siswa.edit', $item) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('panitia.siswa.destroy', $item) }}" method="POST"
                                          style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data siswa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $siswa->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#siswaTable').DataTable({
                "paging": false,
                "searching": false,
                "info": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
@stop
