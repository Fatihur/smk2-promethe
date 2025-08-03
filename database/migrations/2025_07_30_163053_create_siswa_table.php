<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran', 20)->unique();
            $table->string('nisn', 10)->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_telepon', 15)->nullable();
            $table->string('asal_sekolah');
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik');
            $table->foreignId('pilihan_jurusan_1')->constrained('jurusan');
            $table->foreignId('pilihan_jurusan_2')->nullable()->constrained('jurusan');
            $table->enum('kategori', ['khusus', 'umum']);
            $table->enum('status_seleksi', ['pending', 'lulus', 'tidak_lulus', 'lulus_pilihan_2'])->default('pending');
            $table->foreignId('jurusan_diterima_id')->nullable()->constrained('jurusan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
