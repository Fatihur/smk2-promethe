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
        Schema::create('nilai_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria');
            $table->foreignId('sub_kriteria_id')->nullable()->constrained('sub_kriteria');
            $table->decimal('nilai', 5, 2); // Nilai 0-100
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'kriteria_id', 'sub_kriteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_siswa');
    }
};
