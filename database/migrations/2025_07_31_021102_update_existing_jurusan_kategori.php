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
        // Update existing jurusan with kategori values
        $kategoriMapping = [
            'TAB' => 'khusus',    // Teknik Alat Berat - specialized
            'TSM' => 'umum',      // Teknik Sepeda Motor
            'TKR' => 'umum',      // Teknik Kendaraan Ringan
            'TBSM' => 'umum',     // Teknik Body dan Sepeda Motor
            'TKJ' => 'umum',      // Teknik Komputer dan Jaringan
            'RPL' => 'khusus',    // Rekayasa Perangkat Lunak - specialized
            'MM' => 'khusus',     // Multimedia - specialized
            'TGB' => 'umum',      // Teknik Gambar Bangunan
            'TKBB' => 'umum',     // Teknik Konstruksi Batu dan Beton
            'TPTU' => 'umum',     // Teknik Pengelasan dan Fabrikasi Logam
            'TM' => 'umum',       // Teknik Mesin
            'TPFL' => 'umum',     // Teknik Pengelasan dan Fabrikasi Logam
            'TITL' => 'umum',     // Teknik Instalasi Tenaga Listrik
        ];

        foreach ($kategoriMapping as $kode => $kategori) {
            DB::table('jurusan')
                ->where('kode_jurusan', $kode)
                ->update(['kategori' => $kategori]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset all kategori to default 'umum'
        DB::table('jurusan')->update(['kategori' => 'umum']);
    }
};
