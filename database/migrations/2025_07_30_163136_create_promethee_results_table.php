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
        Schema::create('promethee_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik');
            $table->enum('kategori', ['khusus', 'umum']);
            $table->decimal('phi_plus', 10, 6); // Outranking flow
            $table->decimal('phi_minus', 10, 6); // Outranked flow
            $table->decimal('phi_net', 10, 6); // Net flow
            $table->integer('ranking');
            $table->boolean('masuk_kuota')->default(false);
            $table->enum('status_validasi', ['pending', 'lulus', 'lulus_pilihan_2', 'tidak_lulus'])->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users');
            $table->timestamp('validated_at')->nullable();
            $table->text('catatan_validasi')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'tahun_akademik_id', 'kategori']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promethee_results');
    }
};
