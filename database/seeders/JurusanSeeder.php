<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = [
            ['kode_jurusan' => 'TAB', 'nama_jurusan' => 'Teknik Alat Berat', 'deskripsi' => 'Program keahlian Teknik Alat Berat', 'kuota' => 36, 'kategori' => 'khusus'],
            ['kode_jurusan' => 'TSM', 'nama_jurusan' => 'Teknik Sepeda Motor', 'deskripsi' => 'Program keahlian Teknik Sepeda Motor', 'kuota' => 36, 'kategori' => 'umum'],
            ['kode_jurusan' => 'TKR', 'nama_jurusan' => 'Teknik Kendaraan Ringan', 'deskripsi' => 'Program keahlian Teknik Kendaraan Ringan', 'kuota' => 72, 'kategori' => 'umum'],
            ['kode_jurusan' => 'TBSM', 'nama_jurusan' => 'Teknik Body dan Sepeda Motor', 'deskripsi' => 'Program keahlian Teknik Body dan Sepeda Motor', 'kuota' => 36, 'kategori' => 'umum'],
            ['kode_jurusan' => 'TKJ', 'nama_jurusan' => 'Teknik Komputer dan Jaringan', 'deskripsi' => 'Program keahlian Teknik Komputer dan Jaringan', 'kuota' => 72, 'kategori' => 'umum'],
            ['kode_jurusan' => 'RPL', 'nama_jurusan' => 'Rekayasa Perangkat Lunak', 'deskripsi' => 'Program keahlian Rekayasa Perangkat Lunak', 'kuota' => 36, 'kategori' => 'khusus'],
            ['kode_jurusan' => 'MM', 'nama_jurusan' => 'Multimedia', 'deskripsi' => 'Program keahlian Multimedia', 'kuota' => 36, 'kategori' => 'khusus'],
            ['kode_jurusan' => 'TGB', 'nama_jurusan' => 'Teknik Gambar Bangunan', 'deskripsi' => 'Program keahlian Teknik Gambar Bangunan', 'kuota' => 36, 'kategori' => 'umum'],
            ['kode_jurusan' => 'TKBB', 'nama_jurusan' => 'Teknik Konstruksi Batu dan Beton', 'deskripsi' => 'Program keahlian Teknik Konstruksi Batu dan Beton', 'kuota' => 36, 'kategori' => 'umum'],
            ['kode_jurusan' => 'TPTU', 'nama_jurusan' => 'Teknik Pengelasan dan Fabrikasi Logam', 'deskripsi' => 'Program keahlian Teknik Pengelasan dan Fabrikasi Logam', 'kuota' => 36, 'kategori' => 'umum'],
            ['kode_jurusan' => 'TM', 'nama_jurusan' => 'Teknik Mesin', 'deskripsi' => 'Program keahlian Teknik Mesin', 'kuota' => 36, 'kategori' => 'umum'],
            ['kode_jurusan' => 'TPFL', 'nama_jurusan' => 'Teknik Pengelasan dan Fabrikasi Logam', 'deskripsi' => 'Program keahlian Teknik Pengelasan dan Fabrikasi Logam', 'kuota' => 36, 'kategori' => 'umum'],
            ['kode_jurusan' => 'TITL', 'nama_jurusan' => 'Teknik Instalasi Tenaga Listrik', 'deskripsi' => 'Program keahlian Teknik Instalasi Tenaga Listrik', 'kuota' => 72, 'kategori' => 'umum'],
        ];

        foreach ($jurusan as $data) {
            DB::table('jurusan')->insert(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
