@extends('adminlte::page')

@section('title', 'Detail User')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail User</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi User</h3>
                <div class="card-tools">
                    @switch($user->role)
                        @case('admin')
                            <span class="badge badge-danger badge-lg">Admin</span>
                            @break
                        @case('panitia')
                            <span class="badge badge-warning badge-lg">Panitia</span>
                            @break
                        @case('ketua_jurusan')
                            <span class="badge badge-success badge-lg">Ketua Jurusan</span>
                            @break
                    @endswitch
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Nama Lengkap:</strong></td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Username:</strong></td>
                                <td>
                                    <span class="badge badge-info badge-lg">{{ $user->username }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Role:</strong></td>
                                <td>
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="badge badge-danger badge-lg">Admin</span>
                                            <br><small class="text-muted">Akses penuh ke semua fitur</small>
                                            @break
                                        @case('panitia')
                                            <span class="badge badge-warning badge-lg">Panitia</span>
                                            <br><small class="text-muted">Mengelola data siswa dan seleksi</small>
                                            @break
                                        @case('ketua_jurusan')
                                            <span class="badge badge-success badge-lg">Ketua Jurusan</span>
                                            <br><small class="text-muted">Melihat hasil seleksi jurusan</small>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Jurusan:</strong></td>
                                <td>
                                    @if($user->jurusan)
                                        <span class="badge badge-primary">{{ $user->jurusan->kode_jurusan }}</span>
                                        {{ $user->jurusan->nama_jurusan }}
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat:</strong></td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Diperbarui:</strong></td>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge badge-success badge-lg">Aktif</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($user->jurusan)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Jurusan</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Kode Jurusan:</strong></td>
                                <td><span class="badge badge-primary">{{ $user->jurusan->kode_jurusan }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Jurusan:</strong></td>
                                <td>{{ $user->jurusan->nama_jurusan }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Kuota:</strong></td>
                                <td>{{ $user->jurusan->kuota }} siswa</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $user->jurusan->kategori == 'khusus' ? 'warning' : 'info' }}">
                                        {{ ucfirst($user->jurusan->kategori) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($user->jurusan->deskripsi)
                    <div class="mt-3">
                        <strong>Deskripsi Jurusan:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {{ $user->jurusan->deskripsi }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi</h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-block">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    
                    @if($user->id !== auth()->id())
                        <hr>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block"
                                    onclick="return confirm('Yakin ingin menghapus user ini?')">
                                <i class="fas fa-trash"></i> Hapus User
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Anda tidak dapat menghapus akun sendiri.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik</h3>
            </div>
            <div class="card-body">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bergabung</span>
                        <span class="info-box-number">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Terakhir Update</span>
                        <span class="info-box-number">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.75em;
}
.d-grid {
    display: grid;
}
.gap-2 {
    gap: 0.5rem;
}
</style>
@stop
