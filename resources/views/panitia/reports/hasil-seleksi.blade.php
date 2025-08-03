@extends('adminlte::page')

@section('title', 'Laporan Hasil Seleksi')

@section('content_header')
    <h1>Laporan Hasil Seleksi</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list-alt"></i>
            Hasil Seleksi PPDB {{ $tahunAkademik->tahun }} - {{ $tahunAkademik->semester }}
        </h3>
        <div class="card-tools">
            <a href="{{ route('panitia.reports.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('panitia.reports.print.hasil-seleksi', request()->query()) }}" class="btn btn-primary btn-sm" target="_blank">
                <i class="fas fa-print"></i> Cetak
            </a>
            <a href="{{ route('panitia.reports.export.hasil-seleksi', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="kategori">Kategori:</label>
                    <select name="kategori" id="kategori" class="form-control">
                        <option value="all" {{ $kategori == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="khusus" {{ $kategori == 'khusus' ? 'selected' : '' }}>Khusus</option>
                        <option value="umum" {{ $kategori == 'umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="jurusan_id">Jurusan:</label>
                    <select name="jurusan_id" id="jurusan_id" class="form-control">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusan as $j)
                            <option value="{{ $j->id }}" {{ $jurusanId == $j->id ? 'selected' : '' }}>
                                {{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label><br>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('panitia.reports.hasil-seleksi') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Summary -->
        <div class="alert alert-info">
            <div class="row">
                <div class="col-md-3">
                    <strong>Total Siswa:</strong> {{ $siswa->count() }}
                </div>
                <div class="col-md-3">
                    <strong>Lulus:</strong> {{ $siswa->where('status_seleksi', 'lulus')->count() }}
                </div>
                <div class="col-md-3">
                    <strong>Lulus Pilihan 2:</strong> {{ $siswa->where('status_seleksi', 'lulus_pilihan_2')->count() }}
                </div>
                <div class="col-md-3">
                    <strong>Tidak Lulus:</strong> {{ $siswa->where('status_seleksi', 'tidak_lulus')->count() }}
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="hasilTable">
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>L/P</th>
                        <th>Asal Sekolah</th>
                        <th>Pilihan 1</th>
                        <th>Pilihan 2</th>
                        <th>Kategori</th>
                        <th>Status Seleksi</th>
                        <th>Jurusan Diterima</th>
                        <th>Ranking</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $s)
                        @php
                            $prometheusResult = $s->prometheusResults->first();
                        @endphp
                        <tr>
                            <td><strong>{{ $s->no_pendaftaran }}</strong></td>
                            <td>{{ $s->nisn }}</td>
                            <td>{{ $s->nama_lengkap }}</td>
                            <td>{{ $s->jenis_kelamin }}</td>
                            <td>{{ $s->asal_sekolah }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $s->pilihanJurusan1->kode_jurusan }}</span>
                                {{ $s->pilihanJurusan1->nama_jurusan }}
                            </td>
                            <td>
                                @if($s->pilihanJurusan2)
                                    <span class="badge badge-secondary">{{ $s->pilihanJurusan2->kode_jurusan }}</span>
                                    {{ $s->pilihanJurusan2->nama_jurusan }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($s->kategori == 'khusus')
                                    <span class="badge badge-warning">Khusus</span>
                                @else
                                    <span class="badge badge-info">Umum</span>
                                @endif
                            </td>
                            <td>
                                @switch($s->status_seleksi)
                                    @case('pending')
                                        <span class="badge badge-secondary">Pending</span>
                                        @break
                                    @case('lulus')
                                        <span class="badge badge-success">Lulus</span>
                                        @break
                                    @case('lulus_pilihan_2')
                                        <span class="badge badge-info">Lulus Pilihan 2</span>
                                        @break
                                    @case('tidak_lulus')
                                        <span class="badge badge-danger">Tidak Lulus</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @if($s->jurusanDiterima)
                                    <span class="badge badge-success">{{ $s->jurusanDiterima->kode_jurusan }}</span>
                                    {{ $s->jurusanDiterima->nama_jurusan }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($prometheusResult)
                                    <span class="badge badge-primary">#{{ $prometheusResult->ranking }}</span>
                                    <small class="text-muted d-block">{{ number_format($prometheusResult->phi_net, 4) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data siswa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#hasilTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 25,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'copy',
                        className: 'btn btn-secondary btn-sm'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-info btn-sm'
                    }
                ]
            });
        });
    </script>
@stop
