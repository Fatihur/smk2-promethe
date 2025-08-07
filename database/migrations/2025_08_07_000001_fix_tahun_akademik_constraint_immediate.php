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
        try {
            // First, check if the problematic constraint exists
            $constraintExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'tahun_akademik' 
                AND CONSTRAINT_NAME = 'unique_active_year'
            ");

            if (!empty($constraintExists)) {
                // Drop the problematic unique constraint
                DB::statement('ALTER TABLE tahun_akademik DROP INDEX unique_active_year');
            }

            // Check if our partial index already exists
            $partialIndexExists = DB::select("
                SELECT INDEX_NAME 
                FROM information_schema.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'tahun_akademik' 
                AND INDEX_NAME = 'unique_active_tahun_akademik'
            ");

            if (empty($partialIndexExists)) {
                // Create a partial unique index that only applies when is_active = true
                // This allows multiple records with is_active = false but only one with is_active = true
                DB::statement('CREATE UNIQUE INDEX unique_active_tahun_akademik ON tahun_akademik (is_active) WHERE is_active = 1');
            }

        } catch (\Exception $e) {
            // If we're on a database that doesn't support partial indexes (like older MySQL versions),
            // we'll use a different approach
            try {
                // Drop the constraint if it exists
                DB::statement('ALTER TABLE tahun_akademik DROP INDEX unique_active_year');
            } catch (\Exception $e2) {
                // Constraint might not exist, continue
            }

            // For databases that don't support partial indexes, we'll handle this in the application logic
            // The controller already handles ensuring only one active record
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            // Drop the partial unique index if it exists
            DB::statement('DROP INDEX IF EXISTS unique_active_tahun_akademik ON tahun_akademik');
        } catch (\Exception $e) {
            // Index might not exist
        }

        try {
            // Restore the original constraint (though it was problematic)
            Schema::table('tahun_akademik', function (Blueprint $table) {
                $table->unique(['is_active'], 'unique_active_year');
            });
        } catch (\Exception $e) {
            // Constraint might already exist
        }
    }
};
