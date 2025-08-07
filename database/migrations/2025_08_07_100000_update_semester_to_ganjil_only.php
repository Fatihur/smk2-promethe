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
        // Update all existing tahun akademik records to use 'Ganjil' semester
        DB::table('tahun_akademik')->update(['semester' => 'Ganjil']);
        
        // Optionally, you can modify the column to have a default value
        Schema::table('tahun_akademik', function (Blueprint $table) {
            $table->string('semester', 10)->default('Ganjil')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the default value (restore original state)
        Schema::table('tahun_akademik', function (Blueprint $table) {
            $table->string('semester', 10)->default(null)->change();
        });
    }
};
