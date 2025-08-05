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
        // Step 1: Add bobot, nilai_min, nilai_max to master_kriteria
        Schema::table('master_kriteria', function (Blueprint $table) {
            $table->decimal('bobot', 5, 2)->default(1.0)->after('tipe'); // Weight for PROMETHEE calculation
            $table->decimal('nilai_min', 8, 2)->default(0)->after('bobot'); // Minimum value range
            $table->decimal('nilai_max', 8, 2)->default(100)->after('nilai_min'); // Maximum value range
        });

        // Step 2: Migrate existing data from kriteria_jurusan to master_kriteria
        // Get nilai_min and nilai_max from kriteria_jurusan for each master_kriteria
        $kriteriaData = DB::select("
            SELECT
                master_kriteria_id,
                MIN(nilai_min) as min_nilai_min,
                MAX(nilai_max) as max_nilai_max
            FROM kriteria_jurusan
            WHERE is_active = 1
            GROUP BY master_kriteria_id
        ");

        foreach ($kriteriaData as $data) {
            DB::table('master_kriteria')
                ->where('id', $data->master_kriteria_id)
                ->update([
                    'bobot' => 1.0, // Default equal weight
                    'nilai_min' => $data->min_nilai_min ?? 0,
                    'nilai_max' => $data->max_nilai_max ?? 100,
                ]);
        }

        // Step 3: Drop kriteria_jurusan table
        Schema::dropIfExists('kriteria_jurusan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Recreate kriteria_jurusan table
        Schema::create('kriteria_jurusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_kriteria_id')->constrained('master_kriteria')->onDelete('cascade');
            $table->foreignId('jurusan_id')->constrained('jurusan')->onDelete('cascade');
            $table->decimal('nilai_min', 8, 2)->default(0);
            $table->decimal('nilai_max', 8, 2)->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['master_kriteria_id', 'jurusan_id']);
        });

        // Step 2: Migrate data back from master_kriteria to kriteria_jurusan
        $masterKriteria = DB::table('master_kriteria')->where('is_active', true)->get();
        $jurusan = DB::table('jurusan')->where('is_active', true)->get();

        foreach ($masterKriteria as $mk) {
            foreach ($jurusan as $j) {
                DB::table('kriteria_jurusan')->insert([
                    'master_kriteria_id' => $mk->id,
                    'jurusan_id' => $j->id,
                    'nilai_min' => $mk->nilai_min ?? 0,
                    'nilai_max' => $mk->nilai_max ?? 100,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Step 3: Remove columns from master_kriteria
        Schema::table('master_kriteria', function (Blueprint $table) {
            $table->dropColumn(['bobot', 'nilai_min', 'nilai_max']);
        });
    }
};
