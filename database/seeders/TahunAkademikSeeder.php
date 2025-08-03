<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tahun_akademik')->insert([
            'tahun' => '2024/2025',
            'semester' => 'Ganjil',
            'is_active' => true,
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2025-06-30',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
