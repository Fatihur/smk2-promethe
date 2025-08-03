<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'no_pendaftaran',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'email',
        'nama_ayah',
        'asal_sekolah',
        'tahun_akademik_id',
        'pilihan_jurusan_1',
        'pilihan_jurusan_2',
        'kategori',
        'status',
        'status_seleksi',
        'jurusan_diterima_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Get the tahun akademik that the siswa belongs to.
     */
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    /**
     * Get the first choice jurusan.
     */
    public function pilihanJurusan1()
    {
        return $this->belongsTo(Jurusan::class, 'pilihan_jurusan_1');
    }

    /**
     * Get the second choice jurusan.
     */
    public function pilihanJurusan2()
    {
        return $this->belongsTo(Jurusan::class, 'pilihan_jurusan_2');
    }

    /**
     * Get the accepted jurusan.
     */
    public function jurusanDiterima()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_diterima_id');
    }

    /**
     * Get the nilai for the siswa.
     */
    public function nilaiSiswa()
    {
        return $this->hasMany(NilaiSiswa::class);
    }

    /**
     * Get the PROMETHEE results for the siswa.
     */
    public function prometheusResults()
    {
        return $this->hasMany(PrometheusResult::class);
    }

    /**
     * Check if siswa is in kategori khusus (TAB/TSM)
     */
    public function isKategoriKhusus(): bool
    {
        return $this->kategori === 'khusus';
    }

    /**
     * Automatically determine kategori based on pilihan jurusan 1
     */
    public function determineKategori(): string
    {
        $jurusan = $this->pilihanJurusan1;
        if ($jurusan && in_array($jurusan->kode_jurusan, ['TAB', 'TSM'])) {
            return 'khusus';
        }
        return 'umum';
    }

    /**
     * Generate unique registration number
     */
    public static function generateNoPendaftaran($tahunAkademik): string
    {
        $year = substr($tahunAkademik->tahun, 0, 4);
        $lastNumber = static::where('tahun_akademik_id', $tahunAkademik->id)
            ->count() + 1;

        return $year . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope to get siswa by kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope to get siswa by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_seleksi', $status);
    }
}
