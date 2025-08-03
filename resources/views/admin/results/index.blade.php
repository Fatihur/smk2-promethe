@extends('adminlte::page')

@section('title', 'Hasil Seleksi')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Hasil Seleksi</h1>
        <div>
            <span class="badge badge-info">{{ $tahunAkademik->nama_tahun }}</span>
        </div>
    </div>
@stop

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_hasil'] }}</h3>
                <p>Total Hasil</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['total_diterima'] }}</h3>
                <p>Diterima</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['total_tidak_diterima'] }}</h3>
                <p>Tidak Diterima</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['kategori_khusus'] }}</h3>
                <p>Kategori Khusus</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
</div>

<!-- Results Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Hasil Seleksi</h3>
        <div class="card-tools">
            <form method="GET" action="{{ route('admin.results.index') }}" class="form-inline">
                <div class="input-group input-group-sm mr-2" style="width: 150px;">
                    <select name="jurusan_id" class="form-control">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusan as $j)
                            <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                                {{ $j->kode_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group input-group-sm mr-2" style="width: 120px;">
                    <select name="kategori" class="form-control">
                        <option value="">Semua Kategori</option>
                        <option value="khusus" {{ request('kategori') == 'khusus' ? 'selected' : '' }}>Khusus</option>
                        <option value="umum" {{ request('kategori') == 'umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>
                <div class="input-group input-group-sm mr-2" style="width: 120px;">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="tidak_diterima" {{ request('status') == 'tidak_diterima' ? 'selected' : '' }}>Tidak Diterima</option>
                    </select>
                </div>
                <div class="input-group input-group-sm mr-2" style="width: 200px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari siswa..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['jurusan_id', 'kategori', 'status', 'search']))
                    <a href="{{ route('admin.results.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Ranking</th>
                    <th>Siswa</th>
                    <th>Jurusan</th>
                    <th>Kategori</th>
                    <th>Total Skor</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                    <tr>
                        <td>
                            <span class="badge badge-{{ $result->ranking <= 10 ? 'warning' : 'secondary' }}">
                                #{{ $result->ranking }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $result->siswa->nama_lengkap }}</strong>
                            <br><small class="text-muted">{{ $result->siswa->no_pendaftaran }}</small>
                        </td>
                        <td>
                            @if($result->siswa->jurusanDiterima)
                                <span class="badge badge-primary">{{ $result->siswa->jurusanDiterima->kode_jurusan }}</span>
                                {{ $result->siswa->jurusanDiterima->nama_jurusan }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $result->kategori == 'khusus' ? 'warning' : 'info' }}">
                                {{ ucfirst($result->kategori) }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ number_format($result->phi_net, 4) }}</strong>
                        </td>
                        <td>
                            @if($result->masuk_kuota)
                                <span class="badge badge-success">Diterima</span>
                            @else
                                <span class="badge badge-danger">Tidak Diterima</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $result->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="py-4">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada hasil seleksi</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($results->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Menampilkan {{ $results->firstItem() }} sampai {{ $results->lastItem() }} 
                    dari {{ $results->total() }} data
                </div>
                {{ $results->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Auto submit form when filters change
    $('select[name="jurusan_id"], select[name="kategori"], select[name="status"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@stop
