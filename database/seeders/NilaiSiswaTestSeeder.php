<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\MasterKriteria;
use App\Models\NilaiSiswa;

class NilaiSiswaTestSeeder extends Seeder
{
    public function run()
    {
        $siswa = Siswa::all();
        $kriteria = MasterKriteria::where('is_active', true)->get();

        if ($siswa->isEmpty() || $kriteria->isEmpty()) {
            $this->command->error('Tidak ada data siswa atau kriteria yang aktif.');
            return;
        }

        // Clear existing nilai siswa
        NilaiSiswa::truncate();

        foreach ($siswa as $s) {
            foreach ($kriteria as $k) {
                // Generate random nilai based on kriteria type
                $nilai = $this->generateNilai($k->tipe);
                
                NilaiSiswa::create([
                    'siswa_id' => $s->id,
                    'master_kriteria_id' => $k->id,
                    'nilai' => $nilai,
                ]);
            }
        }

        $this->command->info('Test nilai siswa data created successfully!');
        $this->command->line('Total nilai: ' . NilaiSiswa::count());
    }

    private function generateNilai($tipe)
    {
        switch ($tipe) {
            case 'benefit':
                // Higher is better (0-100)
                return rand(60, 100);
            case 'cost':
                // Lower is better (1-50)
                return rand(1, 50);
            default:
                return rand(1, 100);
        }
    }
}
