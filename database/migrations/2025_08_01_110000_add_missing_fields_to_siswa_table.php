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
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('email')->nullable()->after('no_telepon');
            $table->string('nama_ayah')->nullable()->after('alamat');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
            $table->string('pekerjaan_ayah')->nullable()->after('nama_ibu');
            $table->string('pekerjaan_ibu')->nullable()->after('pekerjaan_ayah');
            $table->bigInteger('penghasilan_ortu')->nullable()->after('pekerjaan_ibu');
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending')->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'nama_ayah', 
                'nama_ibu',
                'pekerjaan_ayah',
                'pekerjaan_ibu',
                'penghasilan_ortu',
                'status'
            ]);
        });
    }
};
