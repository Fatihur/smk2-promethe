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
        Schema::table('nilai_siswa', function (Blueprint $table) {
            // Drop the incorrect unique constraint that only uses siswa_id
            $table->dropUnique('nilai_siswa_siswa_id_kriteria_id_sub_kriteria_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_siswa', function (Blueprint $table) {
            // Add back the constraint if needed (though it was incorrect)
            $table->unique(['siswa_id'], 'nilai_siswa_siswa_id_kriteria_id_sub_kriteria_id_unique');
        });
    }
};
