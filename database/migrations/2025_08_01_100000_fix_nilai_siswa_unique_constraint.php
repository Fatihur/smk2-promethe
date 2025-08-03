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
        // First, check if the table exists and has the old constraint
        if (Schema::hasTable('nilai_siswa')) {
            // Drop old unique constraints if they exist
            try {
                Schema::table('nilai_siswa', function (Blueprint $table) {
                    // Try to drop the old unique constraint
                    $table->dropUnique(['siswa_id', 'kriteria_id', 'sub_kriteria_id']);
                });
            } catch (\Exception $e) {
                // Constraint might not exist, continue
            }

            // Remove any duplicate entries before adding new constraint
            $this->removeDuplicateEntries();

            // Add new unique constraint for siswa_id and master_kriteria_id
            Schema::table('nilai_siswa', function (Blueprint $table) {
                $table->unique(['siswa_id', 'master_kriteria_id'], 'nilai_siswa_siswa_master_kriteria_unique');
            });
        }
    }

    /**
     * Remove duplicate entries based on siswa_id and master_kriteria_id
     */
    private function removeDuplicateEntries()
    {
        // Find and remove duplicates, keeping only the latest entry
        $duplicates = DB::select("
            SELECT siswa_id, master_kriteria_id, COUNT(*) as count
            FROM nilai_siswa 
            WHERE master_kriteria_id IS NOT NULL
            GROUP BY siswa_id, master_kriteria_id 
            HAVING COUNT(*) > 1
        ");

        foreach ($duplicates as $duplicate) {
            // Keep only the latest entry (highest ID)
            $keepId = DB::table('nilai_siswa')
                ->where('siswa_id', $duplicate->siswa_id)
                ->where('master_kriteria_id', $duplicate->master_kriteria_id)
                ->max('id');

            // Delete older entries
            DB::table('nilai_siswa')
                ->where('siswa_id', $duplicate->siswa_id)
                ->where('master_kriteria_id', $duplicate->master_kriteria_id)
                ->where('id', '!=', $keepId)
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('nilai_siswa')) {
            Schema::table('nilai_siswa', function (Blueprint $table) {
                $table->dropUnique('nilai_siswa_siswa_master_kriteria_unique');
            });
        }
    }
};
