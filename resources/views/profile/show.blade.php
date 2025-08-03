@extends('adminlte::page')

@section('title', 'Profil Saya')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Profil Saya</h1>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Profil
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Profil</h3>
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
                                <td><strong>Bergabung:</strong></td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Update:</strong></td>
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
                <h3 class="card-title">Aksi Profil</h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                    
                    <hr>
                    
                    <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#changePasswordModal">
                        <i class="fas fa-key"></i> Ubah Password
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aktivitas</h3>
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

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" id="current_password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               placeholder="Masukkan password saat ini" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Masukkan password baru (minimal 8 karakter)" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control" 
                               placeholder="Ulangi password baru" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key"></i> Ubah Password
                    </button>
                </div>
            </form>
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

@section('js')
<script>
@if($errors->has('current_password') || $errors->has('password'))
    $(document).ready(function() {
        $('#changePasswordModal').modal('show');
    });
@endif
</script>
@stop
