@extends('adminlte::page')

@section('title', 'Edit Siswa')

@section('content_header')
    <h1>Edit Data Siswa</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-edit mr-2"></i>
            Form Edit Siswa
        </h3>
    </div>
    <form action="{{ route('panitia.siswa.update', $siswa) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <!-- Data Pribadi -->
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-user"></i> Data Pribadi
                    </h5>
                    
                    <div class="form-group">
                        <label for="no_pendaftaran">No. Pendaftaran</label>
                        <input type="text" class="form-control" 
                               id="no_pendaftaran" name="no_pendaftaran" value="{{ $siswa->no_pendaftaran }}" readonly>
                        <small class="form-text text-muted">No. pendaftaran tidak dapat diubah</small>
                    </div>

                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                               id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required>
                        @error('nama_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nisn">NISN <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nisn') is-invalid @enderror" 
                               id="nisn" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" required>
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-control @error('jenis_kelamin') is-invalid @enderror" 
                                id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                               id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" required>
                        @error('tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                               id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}" required>
                        @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="asal_sekolah">Asal Sekolah <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror" 
                               id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah', $siswa->asal_sekolah) }}" required>
                        @error('asal_sekolah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Pilihan Jurusan -->
                <div class="col-md-6">
                    <h5 class="text-success mb-3">
                        <i class="fas fa-graduation-cap"></i> Pilihan Jurusan
                    </h5>

                    <div class="form-group">
                        <label for="pilihan_jurusan_1">Pilihan Jurusan 1 <span class="text-danger">*</span></label>
                        <select class="form-control @error('pilihan_jurusan_1') is-invalid @enderror" 
                                id="pilihan_jurusan_1" name="pilihan_jurusan_1" required>
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id }}" {{ old('pilihan_jurusan_1', $siswa->pilihan_jurusan_1) == $j->id ? 'selected' : '' }}>
                                    {{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('pilihan_jurusan_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="pilihan_jurusan_2">Pilihan Jurusan 2</label>
                        <select class="form-control @error('pilihan_jurusan_2') is-invalid @enderror" 
                                id="pilihan_jurusan_2" name="pilihan_jurusan_2">
                            <option value="">Pilih Jurusan (Opsional)</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id }}" {{ old('pilihan_jurusan_2', $siswa->pilihan_jurusan_2) == $j->id ? 'selected' : '' }}>
                                    {{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('pilihan_jurusan_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kategori">Kategori Pendaftar</label>
                        <input type="text" class="form-control @error('kategori') is-invalid @enderror"
                               id="kategori" name="kategori" value="{{ old('kategori', $siswa->kategori) }}" readonly>
                        <small class="form-text text-muted">Kategori akan otomatis terisi berdasarkan pilihan jurusan 1</small>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="3">{{ old('alamat', $siswa->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $siswa->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama_ayah">Nama Ayah</label>
                        <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror"
                               id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah', $siswa->nama_ayah) }}">
                        @error('nama_ayah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="no_telepon">No. Telepon</label>
                        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                               id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $siswa->no_telepon) }}">
                        @error('no_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('panitia.siswa.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Data jurusan khusus (TAB dan TSM)
    var jurusanKhusus = @json($jurusan->whereIn('kode_jurusan', ['TAB', 'TSM'])->pluck('id')->toArray());

    // Function to update kategori based on pilihan jurusan 1
    function updateKategori() {
        var pilihanJurusan1 = $('#pilihan_jurusan_1').val();
        var kategoriField = $('#kategori');

        if (pilihanJurusan1) {
            if (jurusanKhusus.includes(parseInt(pilihanJurusan1))) {
                kategoriField.val('khusus');
            } else {
                kategoriField.val('umum');
            }
        } else {
            kategoriField.val('umum');
        }
    }

    // Update kategori when pilihan jurusan 1 changes
    $('#pilihan_jurusan_1').on('change', function() {
        updateKategori();
    });

    // Prevent same jurusan selection
    $('#pilihan_jurusan_1, #pilihan_jurusan_2').on('change', function() {
        var pilihan1 = $('#pilihan_jurusan_1').val();
        var pilihan2 = $('#pilihan_jurusan_2').val();

        if (pilihan1 && pilihan2 && pilihan1 === pilihan2) {
            alert('Pilihan jurusan 1 dan 2 tidak boleh sama!');
            $(this).val('');
            // Update kategori after clearing selection
            if ($(this).attr('id') === 'pilihan_jurusan_1') {
                updateKategori();
            }
        }
    });
});
</script>
@stop
