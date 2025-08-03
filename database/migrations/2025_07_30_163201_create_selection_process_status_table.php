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
        Schema::create('selection_process_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik');
            $table->enum('kategori_khusus_status', ['belum_dimulai', 'sedang_berjalan', 'selesai'])->default('belum_dimulai');
            $table->enum('kategori_umum_status', ['tidak_aktif', 'siap', 'sedang_berjalan', 'selesai'])->default('tidak_aktif');
            $table->integer('kuota_khusus')->nullable();
            $table->timestamp('khusus_started_at')->nullable();
            $table->timestamp('khusus_completed_at')->nullable();
            $table->timestamp('umum_started_at')->nullable();
            $table->timestamp('umum_completed_at')->nullable();
            $table->timestamps();

            $table->unique('tahun_akademik_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selection_process_status');
    }
};
