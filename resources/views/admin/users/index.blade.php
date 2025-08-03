@extends('adminlte::page')

@section('title', 'Manajemen Pengguna')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Manajemen Pengguna</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools">
            <form method="GET" action="{{ route('admin.users.index') }}" class="form-inline">
                <div class="input-group input-group-sm mr-2" style="width: 150px;">
                    <select name="role" class="form-control">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="panitia" {{ request('role') == 'panitia' ? 'selected' : '' }}>Panitia</option>
                        <option value="ketua_jurusan" {{ request('role') == 'ketua_jurusan' ? 'selected' : '' }}>Ketua Jurusan</option>
                    </select>
                </div>
                <div class="input-group input-group-sm mr-2" style="width: 200px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari user..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                @if(request('role') || request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
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
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $user->username }}</span>
                        </td>
                        <td>{{ $user->email }}</td>
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
                                @default
                                    <span class="badge badge-secondary">{{ ucfirst($user->role) }}</span>
                            @endswitch
                        </td>
                        <td>
                            @if($user->jurusan)
                                <span class="badge badge-primary">{{ $user->jurusan->kode_jurusan }}</span>
                                {{ $user->jurusan->nama_jurusan }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus user ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data pengguna</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah User Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} 
                    dari {{ $users->total() }} data
                </div>
                {{ $users->appends(request()->query())->links() }}
            </div>
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

@section('js')
<script>
$(document).ready(function() {
    // Auto submit form when role filter changes
    $('select[name="role"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@stop
