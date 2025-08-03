@extends('adminlte::page')

@section('title', 'Edit Profil')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Edit Profil</h1>
        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Profil</h3>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" 
                               placeholder="Masukkan nama lengkap" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       value="{{ old('username', $user->username) }}" 
                                       placeholder="Username untuk login" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" 
                                       placeholder="alamat@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                        <small class="form-text text-muted">Role tidak dapat diubah. Hubungi administrator jika perlu mengubah role.</small>
                    </div>

                    @if($user->role === 'ketua_jurusan')
                        <div class="form-group">
                            <label for="jurusan_id">Jurusan <span class="text-danger">*</span></label>
                            <select name="jurusan_id" id="jurusan_id" class="form-control @error('jurusan_id') is-invalid @enderror" required>
                                <option value="">Pilih Jurusan</option>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id }}" {{ old('jurusan_id', $user->jurusan_id) == $j->id ? 'selected' : '' }}>
                                        {{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jurusan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Wajib diisi untuk role Ketua Jurusan</small>
                        </div>
                    @else
                        <div class="form-group">
                            <label for="jurusan">Jurusan</label>
                            <input type="text" class="form-control" 
                                   value="{{ $user->jurusan ? $user->jurusan->nama_jurusan : 'Tidak ada' }}" readonly>
                            <small class="form-text text-muted">Jurusan hanya dapat diatur untuk role Ketua Jurusan.</small>
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Saat Ini</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Username:</strong></td>
                        <td>{{ $user->username }}</td>
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
                                    <span class="badge badge-danger">Admin</span>
                                    @break
                                @case('panitia')
                                    <span class="badge badge-warning">Panitia</span>
                                    @break
                                @case('ketua_jurusan')
                                    <span class="badge badge-success">Ketua Jurusan</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Jurusan:</strong></td>
                        <td>
                            @if($user->jurusan)
                                {{ $user->jurusan->nama_jurusan }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Bergabung:</strong></td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Catatan:</h6>
                    <ul class="mb-0">
                        <li>Username dan email harus unik</li>
                        <li>Role tidak dapat diubah sendiri</li>
                        <li>Untuk mengubah password, gunakan tombol "Ubah Password" di halaman profil</li>
                        @if($user->role === 'ketua_jurusan')
                            <li>Ketua Jurusan harus memilih jurusan</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi Lainnya</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('profile.show') }}" class="btn btn-info btn-block">
                    <i class="fas fa-eye"></i> Lihat Profil
                </a>
                
                <button type="button" class="btn btn-warning btn-block mt-2" data-toggle="modal" data-target="#changePasswordModal">
                    <i class="fas fa-key"></i> Ubah Password
                </button>
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

@section('js')
<script>
@if($errors->has('current_password') || $errors->has('password'))
    $(document).ready(function() {
        $('#changePasswordModal').modal('show');
    });
@endif
</script>
@stop
