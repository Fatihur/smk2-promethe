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
                'no_telepon' => '081234567890',
                'email' => 'siswa1@example.com',
                'nama_ayah' => 'Budi Santoso',
                'nama_ibu' => 'Siti Rahayu',
                'pekerjaan_ayah' => 'Pegawai Swasta',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'penghasilan_ortu' => 5000000,
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
                'no_telepon' => '081234567891',
                'email' => 'siswa2@example.com',
                'nama_ayah' => 'Rizki Wijaya',
                'nama_ibu' => 'Dewi Sartika',
                'pekerjaan_ayah' => 'Guru',
                'pekerjaan_ibu' => 'Perawat',
                'penghasilan_ortu' => 7000000,
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
                'no_telepon' => '081234567892',
                'email' => 'siswa3@example.com',
                'nama_ayah' => 'Indra Permana',
                'nama_ibu' => 'Sari Wulandari',
                'pekerjaan_ayah' => 'Dokter',
                'pekerjaan_ibu' => 'Bidan',
                'penghasilan_ortu' => 12000000,
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
                'no_telepon' => '081234567893',
                'email' => 'siswa4@example.com',
                'nama_ayah' => 'Kurnia Setiawan',
                'nama_ibu' => 'Rina Marlina',
                'pekerjaan_ayah' => 'Wiraswasta',
                'pekerjaan_ibu' => 'Guru',
                'penghasilan_ortu' => 8000000,
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
                'no_telepon' => '081234567894',
                'email' => 'siswa5@example.com',
                'nama_ayah' => 'Sari Gunawan',
                'nama_ibu' => 'Maya Kusuma',
                'pekerjaan_ayah' => 'PNS',
                'pekerjaan_ibu' => 'Dosen',
                'penghasilan_ortu' => 9000000,
                'asal_sekolah' => 'SMP Negeri 5 Yogyakarta',
                'kategori' => 'umum',
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
