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
        // Remove unnecessary fields from siswa table
        Schema::table('siswa', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('siswa', 'nama_ibu')) {
                $table->dropColumn('nama_ibu');
            }
            if (Schema::hasColumn('siswa', 'pekerjaan_ayah')) {
                $table->dropColumn('pekerjaan_ayah');
            }
            if (Schema::hasColumn('siswa', 'pekerjaan_ibu')) {
                $table->dropColumn('pekerjaan_ibu');
            }
            if (Schema::hasColumn('siswa', 'penghasilan_ortu')) {
                $table->dropColumn('penghasilan_ortu');
            }
            if (Schema::hasColumn('siswa', 'no_telepon')) {
                $table->dropColumn('no_telepon');
            }
        });

        // Drop guru table if it exists
        Schema::dropIfExists('guru');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate guru table
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('no_telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('mata_pelajaran')->nullable();
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add back the removed fields to siswa table
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
            $table->string('pekerjaan_ayah')->nullable()->after('nama_ibu');
            $table->string('pekerjaan_ibu')->nullable()->after('pekerjaan_ayah');
            $table->decimal('penghasilan_ortu', 15, 2)->nullable()->after('pekerjaan_ibu');
            $table->string('no_telepon', 15)->nullable()->after('penghasilan_ortu');
        });
    }
};
