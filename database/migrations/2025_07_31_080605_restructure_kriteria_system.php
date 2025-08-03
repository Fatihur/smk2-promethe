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
        // Step 1: Create new master_kriteria table (global criteria without jurusan_id)
        Schema::create('master_kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria', 10)->unique();
            $table->string('nama_kriteria');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['benefit', 'cost'])->default('benefit');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Step 2: Create pivot table for kriteria weights per jurusan
        Schema::create('kriteria_jurusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_kriteria_id')->constrained('master_kriteria')->onDelete('cascade');
            $table->foreignId('jurusan_id')->constrained('jurusan')->onDelete('cascade');
            $table->decimal('bobot', 5, 2); // Weight percentage (0-100)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure unique combination of kriteria and jurusan
            $table->unique(['master_kriteria_id', 'jurusan_id']);
        });

        // Step 3: Migrate existing data from kriterias to new structure
        if (Schema::hasTable('kriterias')) {
            // First, create master criteria from existing unique criteria names
            $existingKriteria = DB::table('kriterias')
                ->select('kode_kriteria', 'nama_kriteria', 'deskripsi', 'tipe')
                ->groupBy('kode_kriteria', 'nama_kriteria', 'deskripsi', 'tipe')
                ->get();

            foreach ($existingKriteria as $kriteria) {
                DB::table('master_kriteria')->insert([
                    'kode_kriteria' => $kriteria->kode_kriteria,
                    'nama_kriteria' => $kriteria->nama_kriteria,
                    'deskripsi' => $kriteria->deskripsi,
                    'tipe' => $kriteria->tipe,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Then, migrate the weights to kriteria_jurusan table
            $allKriteria = DB::table('kriterias')->get();
            foreach ($allKriteria as $kriteria) {
                $masterKriteriaId = DB::table('master_kriteria')
                    ->where('kode_kriteria', $kriteria->kode_kriteria)
                    ->value('id');

                if ($masterKriteriaId) {
                    DB::table('kriteria_jurusan')->insert([
                        'master_kriteria_id' => $masterKriteriaId,
                        'jurusan_id' => $kriteria->jurusan_id,
                        'bobot' => $kriteria->bobot,
                        'is_active' => $kriteria->is_active,
                        'created_at' => $kriteria->created_at,
                        'updated_at' => $kriteria->updated_at,
                    ]);
                }
            }
        }

        // Step 4: Update nilai_siswa table to reference master_kriteria
        if (Schema::hasTable('nilai_siswa')) {
            Schema::table('nilai_siswa', function (Blueprint $table) {
                $table->foreignId('master_kriteria_id')->nullable()->after('kriteria_id')->constrained('master_kriteria')->onDelete('cascade');
            });

            // Migrate existing nilai_siswa data
            $nilaiSiswa = DB::table('nilai_siswa')->get();
            foreach ($nilaiSiswa as $nilai) {
                $kriteria = DB::table('kriterias')->where('id', $nilai->kriteria_id)->first();
                if ($kriteria) {
                    $masterKriteriaId = DB::table('master_kriteria')
                        ->where('kode_kriteria', $kriteria->kode_kriteria)
                        ->value('id');

                    if ($masterKriteriaId) {
                        DB::table('nilai_siswa')
                            ->where('id', $nilai->id)
                            ->update(['master_kriteria_id' => $masterKriteriaId]);
                    }
                }
            }

            // Remove old kriteria_id column after migration
            Schema::table('nilai_siswa', function (Blueprint $table) {
                $table->dropForeign(['kriteria_id']);
                $table->dropColumn('kriteria_id');
            });
        }

        // Step 5: Drop old kriterias table
        Schema::dropIfExists('kriterias');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate kriterias table
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->constrained('jurusan')->onDelete('cascade');
            $table->string('kode_kriteria', 10);
            $table->string('nama_kriteria');
            $table->text('deskripsi')->nullable();
            $table->decimal('bobot', 5, 2);
            $table->enum('tipe', ['benefit', 'cost'])->default('benefit');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['jurusan_id', 'kode_kriteria']);
        });

        // Migrate data back from new structure
        if (Schema::hasTable('kriteria_jurusan') && Schema::hasTable('master_kriteria')) {
            $kriteriaJurusan = DB::table('kriteria_jurusan')
                ->join('master_kriteria', 'kriteria_jurusan.master_kriteria_id', '=', 'master_kriteria.id')
                ->select('kriteria_jurusan.*', 'master_kriteria.kode_kriteria', 'master_kriteria.nama_kriteria',
                        'master_kriteria.deskripsi', 'master_kriteria.tipe')
                ->get();

            foreach ($kriteriaJurusan as $kj) {
                DB::table('kriterias')->insert([
                    'jurusan_id' => $kj->jurusan_id,
                    'kode_kriteria' => $kj->kode_kriteria,
                    'nama_kriteria' => $kj->nama_kriteria,
                    'deskripsi' => $kj->deskripsi,
                    'bobot' => $kj->bobot,
                    'tipe' => $kj->tipe,
                    'is_active' => $kj->is_active,
                    'created_at' => $kj->created_at,
                    'updated_at' => $kj->updated_at,
                ]);
            }
        }

        // Restore nilai_siswa table
        if (Schema::hasTable('nilai_siswa')) {
            Schema::table('nilai_siswa', function (Blueprint $table) {
                $table->foreignId('kriteria_id')->nullable()->after('master_kriteria_id')->constrained('kriterias')->onDelete('cascade');
            });

            // Migrate nilai_siswa data back
            $nilaiSiswa = DB::table('nilai_siswa')->get();
            foreach ($nilaiSiswa as $nilai) {
                $masterKriteria = DB::table('master_kriteria')->where('id', $nilai->master_kriteria_id)->first();
                if ($masterKriteria) {
                    $kriteriaId = DB::table('kriterias')
                        ->where('kode_kriteria', $masterKriteria->kode_kriteria)
                        ->value('id');

                    if ($kriteriaId) {
                        DB::table('nilai_siswa')
                            ->where('id', $nilai->id)
                            ->update(['kriteria_id' => $kriteriaId]);
                    }
                }
            }

            Schema::table('nilai_siswa', function (Blueprint $table) {
                $table->dropForeign(['master_kriteria_id']);
                $table->dropColumn('master_kriteria_id');
            });
        }

        // Drop new tables
        Schema::dropIfExists('kriteria_jurusan');
        Schema::dropIfExists('master_kriteria');
    }
};
