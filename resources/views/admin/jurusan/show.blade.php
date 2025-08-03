@extends('adminlte::page')

@section('title', 'Detail Jurusan')

@section('content_header')
    <h1>Detail Jurusan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $jurusan->nama_jurusan }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.jurusan.edit', $jurusan) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Kode Jurusan</th>
                        <td>: <span class="badge badge-info">{{ $jurusan->kode_jurusan }}</span></td>
                    </tr>
                    <tr>
                        <th>Nama Jurusan</th>
                        <td>: {{ $jurusan->nama_jurusan }}</td>
                    </tr>
                    <tr>
                        <th>Kuota</th>
                        <td>: {{ $jurusan->kuota }} siswa</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: 
                            @if($jurusan->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>: {{ $jurusan->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui</th>
                        <td>: {{ $jurusan->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Deskripsi</h5>
                <p>{{ $jurusan->deskripsi ?: 'Tidak ada deskripsi' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Pendaftar</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <span class="description-percentage text-success">
                                <i class="fas fa-caret-up"></i> 0
                            </span>
                            <h5 class="description-header">0</h5>
                            <span class="description-text">Pilihan 1</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <span class="description-percentage text-warning">
                                <i class="fas fa-caret-left"></i> 0
                            </span>
                            <h5 class="description-header">0</h5>
                            <span class="description-text">Pilihan 2</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ketua Jurusan</h3>
            </div>
            <div class="card-body">
                @if($jurusan->users->where('role', 'ketua_jurusan')->first())
                    @php $ketua = $jurusan->users->where('role', 'ketua_jurusan')->first(); @endphp
                    <p><strong>{{ $ketua->name }}</strong></p>
                    <p>{{ $ketua->email }}</p>
                    <p>
                        @if($ketua->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Tidak Aktif</span>
                        @endif
                    </p>
                @else
                    <p class="text-muted">Belum ada ketua jurusan yang ditugaskan</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status Seleksi</h3>
            </div>
            <div class="card-body">
                <p><strong>Kategori:</strong> 
                    @if(in_array($jurusan->kode_jurusan, ['TAB', 'TSM']))
                        <span class="badge badge-warning">Khusus</span>
                    @else
                        <span class="badge badge-info">Umum</span>
                    @endif
                </p>
                <p><strong>Proses:</strong> <span class="badge badge-secondary">Belum Dimulai</span></p>
                <p><strong>Diterima:</strong> 0 dari {{ $jurusan->kuota }} siswa</p>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    {{-- Add here extra scripts --}}
@stop
