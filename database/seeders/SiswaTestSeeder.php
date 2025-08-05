<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\TahunAkademik;

class SiswaTestSeeder extends Seeder
{
    public function run()
    {
        $tahunAktif = TahunAkademik::where('is_active', true)->first();
        $jurusanList = Jurusan::where('is_active', true)->get();

        if (!$tahunAktif || $jurusanList->isEmpty()) {
            $this->command->error('Tidak ada tahun akademik aktif atau jurusan aktif.');
            return;
        }

        $siswaData = [
            [
                'no_pendaftaran' => '20240001',
                'nisn' => '3451056465',
                'nama_lengkap' => 'Sit Nam sunt nisi ut',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-05-15',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'email' => 'siswa1@example.com',
                'nama_ayah' => 'Budi Santoso',
                'asal_sekolah' => 'SMP Negeri 1 Jakarta',
                'kategori' => 'umum',
                'status' => 'diterima',
            ],
            [
                'no_pendaftaran' => '20240002',
                'nisn' => '3451056466',
                'nama_lengkap' => 'Ahmad Rizki Pratama',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2008-03-20',
                'alamat' => 'Jl. Sudirman No. 456, Bandung',
                'email' => 'siswa2@example.com',
                'nama_ayah' => 'Rizki Wijaya',
                'asal_sekolah' => 'SMP Negeri 2 Bandung',
                'kategori' => 'umum',
                'status' => 'diterima',
            ],
            [
                'no_pendaftaran' => '20240003',
                'nisn' => '3451056467',
                'nama_lengkap' => 'Sari Indah Permata',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2008-07-10',
                'alamat' => 'Jl. Pahlawan No. 789, Surabaya',
                'email' => 'siswa3@example.com',
                'nama_ayah' => 'Indra Permana',
                'asal_sekolah' => 'SMP Negeri 3 Surabaya',
                'kategori' => 'khusus',
                'status' => 'diterima',
            ],
            [
                'no_pendaftaran' => '20240004',
                'nisn' => '3451056468',
                'nama_lengkap' => 'Dedi Kurniawan',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '2008-01-25',
                'alamat' => 'Jl. Gatot Subroto No. 321, Medan',
                'email' => 'siswa4@example.com',
                'nama_ayah' => 'Kurnia Setiawan',
                'asal_sekolah' => 'SMP Negeri 4 Medan',
                'kategori' => 'umum',
                'status' => 'diterima',
            ],
            [
                'no_pendaftaran' => '20240005',
                'nisn' => '3451056469',
                'nama_lengkap' => 'Maya Sari Dewi',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2008-09-12',
                'alamat' => 'Jl. Malioboro No. 654, Yogyakarta',
                'email' => 'siswa5@example.com',
                'nama_ayah' => 'Sari Gunawan',
                'asal_sekolah' => 'SMP Negeri 5 Yogyakarta',
                'kategori' => 'khusus',
                'status' => 'diterima',
            ],
            [
                'no_pendaftaran' => '20240006',
                'nisn' => '3451056470',
                'nama_lengkap' => 'Andi Pratama',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Makassar',
                'tanggal_lahir' => '2008-04-18',
                'alamat' => 'Jl. Veteran No. 987, Makassar',
                'email' => 'siswa6@example.com',
                'nama_ayah' => 'Pratama Wijaya',
                'asal_sekolah' => 'SMP Negeri 6 Makassar',
                'kategori' => 'khusus',
                'status' => 'diterima',
            ],
        ];

        foreach ($siswaData as $index => $data) {
            // Assign to different jurusan cyclically
            $jurusan = $jurusanList[$index % $jurusanList->count()];
            
            $data['tahun_akademik_id'] = $tahunAktif->id;
            $data['pilihan_jurusan_1'] = $jurusan->id;
            $data['pilihan_jurusan_2'] = $jurusanList[($index + 1) % $jurusanList->count()]->id;
            $data['created_at'] = now();
            $data['updated_at'] = now();

            Siswa::updateOrCreate(
                ['no_pendaftaran' => $data['no_pendaftaran']],
                $data
            );
        }

        $this->command->info('Test siswa data created successfully!');
        $this->command->line('Total siswa: ' . Siswa::count());
    }
}
