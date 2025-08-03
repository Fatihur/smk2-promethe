@extends('adminlte::page')

@section('title', 'Daftar Siswa - ' . $jurusan->nama_jurusan)

@section('content_header')
    <h1>
        Daftar Siswa - {{ $jurusan->nama_jurusan }}
        <small class="text-muted">{{ $tahunAktif->tahun_akademik }}</small>
    </h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users"></i>
                    Daftar Siswa
                </h3>
                <div class="card-tools">
                    <a href="{{ route('panitia.nilai-jurusan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if($siswa->isNotEmpty())
                        <a href="{{ route('panitia.nilai-jurusan.bulk', $jurusan) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-table"></i> Input Massal
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($kriteriaJurusan->isEmpty())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Belum ada kriteria penilaian yang aktif untuk jurusan ini. 
                        Hubungi administrator untuk menambahkan kriteria.
                    </div>
                @elseif($siswa->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Belum ada siswa yang mendaftar di jurusan ini untuk tahun akademik {{ $tahunAktif->tahun_akademik }}.
                    </div>
                @else
                    <!-- Kriteria Info -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Kriteria Penilaian:</h6>
                        <div class="row">
                            @foreach($kriteriaJurusan as $kj)
                                <div class="col-md-4 mb-2">
                                    <span class="badge badge-primary">{{ $kj->masterKriteria->nama_kriteria }}</span>
                                    <small class="text-muted">({{ $kj->nilai_min }}-{{ $kj->nilai_max }})</small>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Siswa Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="siswaTable">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>No. Pendaftaran</th>
                                    <th>NISN</th>
                                    <th>Nama Lengkap</th>
                                    <th>Kategori</th>
                                    <th width="15%">Progress</th>
                                    <th width="15%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa as $index => $s)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $s->no_pendaftaran }}</td>
                                        <td>{{ $s->nisn }}</td>
                                        <td>
                                            <strong>{{ $s->nama_lengkap }}</strong>
                                            <br><small class="text-muted">{{ $s->asal_sekolah }}</small>
                                        </td>
                                        <td>
                                            @if($s->kategori == 'khusus')
                                                <span class="badge badge-warning">Khusus</span>
                                            @else
                                                <span class="badge badge-info">Umum</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="progress mb-1" style="height: 20px;">
                                                <div class="progress-bar {{ $s->is_complete ? 'bg-success' : 'bg-primary' }}" 
                                                     style="width: {{ $s->progress_persen }}%">
                                                    {{ $s->progress_persen }}%
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ $s->nilaiSiswa->whereIn('master_kriteria_id', $kriteriaJurusan->pluck('master_kriteria_id'))->count() }} 
                                                / {{ $kriteriaJurusan->count() }} kriteria
                                            </small>
                                        </td>
                                        <td>
                                            @if($s->is_complete)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Lengkap
                                                </span>
                                            @elseif($s->progress_persen > 0)
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock"></i> Sebagian
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-minus"></i> Belum
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('panitia.nilai-jurusan.input', [$jurusan, $s]) }}" 
                                                   class="btn btn-primary btn-sm" 
                                                   data-toggle="tooltip" 
                                                   title="Input/Edit Nilai">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($s->progress_persen > 0)
                                                    <a href="{{ route('panitia.siswa.nilai.index', $s) }}" 
                                                       class="btn btn-info btn-sm" 
                                                       data-toggle="tooltip" 
                                                       title="Lihat Detail Nilai">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
        .progress {
            height: 20px;
        }
        .badge {
            font-size: 0.75rem;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            @if($siswa->isNotEmpty())
            $('#siswaTable').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 25,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                },
                "columnDefs": [
                    { "orderable": false, "targets": [5, 6, 7] }
                ],
                "order": [[3, 'asc']]
            });
            @endif

            // Add tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
