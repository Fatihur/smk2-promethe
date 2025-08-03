<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update kriteria_jurusan table: replace bobot with rentang nilai
        Schema::table('kriteria_jurusan', function (Blueprint $table) {
            // Add new columns for rentang nilai
            $table->decimal('nilai_min', 5, 2)->default(0)->after('bobot');
            $table->decimal('nilai_max', 5, 2)->default(100)->after('nilai_min');
        });

        // Migrate existing bobot data to rentang nilai
        // For now, we'll set nilai_min = 0 and nilai_max = bobot value
        // This can be adjusted later by admin
        $kriteriaJurusan = DB::table('kriteria_jurusan')->get();
        foreach ($kriteriaJurusan as $kj) {
            DB::table('kriteria_jurusan')
                ->where('id', $kj->id)
                ->update([
                    'nilai_min' => 0,
                    'nilai_max' => $kj->bobot ?: 100, // Use bobot as max value, or 100 if null
                ]);
        }

        // Remove bobot column
        Schema::table('kriteria_jurusan', function (Blueprint $table) {
            $table->dropColumn('bobot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back bobot column
        Schema::table('kriteria_jurusan', function (Blueprint $table) {
            $table->decimal('bobot', 5, 2)->default(0)->after('jurusan_id');
        });

        // Migrate rentang nilai back to bobot
        // Use the difference between max and min as bobot
        $kriteriaJurusan = DB::table('kriteria_jurusan')->get();
        foreach ($kriteriaJurusan as $kj) {
            $bobot = $kj->nilai_max - $kj->nilai_min;
            DB::table('kriteria_jurusan')
                ->where('id', $kj->id)
                ->update(['bobot' => $bobot]);
        }

        // Remove rentang nilai columns
        Schema::table('kriteria_jurusan', function (Blueprint $table) {
            $table->dropColumn(['nilai_min', 'nilai_max']);
        });
    }
};
