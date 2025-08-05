<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';

    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
        'deskripsi',
        'kuota',
        'kategori',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users for the jurusan.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all active master kriteria (global criteria, not per jurusan)
     */
    public function getActiveKriteriasAttribute()
    {
        return MasterKriteria::where('is_active', true)
                            ->orderBy('kode_kriteria')
                            ->get();
    }

    /**
     * Get the siswa with this jurusan as pilihan 1.
     */
    public function siswaPilihan1()
    {
        return $this->hasMany(Siswa::class, 'pilihan_jurusan_1');
    }

    /**
     * Get the siswa with this jurusan as pilihan 2.
     */
    public function siswaPilihan2()
    {
        return $this->hasMany(Siswa::class, 'pilihan_jurusan_2');
    }

    /**
     * Get the siswa accepted to this jurusan.
     */
    public function siswaAccepted()
    {
        return $this->hasMany(Siswa::class, 'jurusan_diterima_id');
    }

    /**
     * Get the PROMETHEE results for siswa accepted to this jurusan.
     */
    public function prometheusResults()
    {
        return $this->hasManyThrough(
            PrometheusResult::class,
            Siswa::class,
            'jurusan_diterima_id', // Foreign key on siswa table
            'siswa_id', // Foreign key on promethee_results table
            'id', // Local key on jurusan table
            'id' // Local key on siswa table
        );
    }

    /**
     * Scope to get active jurusan
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get jurusan by kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope to get umum jurusan
     */
    public function scopeUmum($query)
    {
        return $query->where('kategori', 'umum');
    }

    /**
     * Scope to get khusus jurusan
     */
    public function scopeKhusus($query)
    {
        return $query->where('kategori', 'khusus');
    }

    /**
     * Get nama attribute (alias for nama_jurusan)
     */
    public function getNamaAttribute()
    {
        return $this->nama_jurusan;
    }

    /**
     * Get kategori label for display
     */
    public function getKategoriLabelAttribute()
    {
        return $this->kategori === 'umum' ? 'Umum' : 'Khusus';
    }

    /**
     * Check if jurusan is umum
     */
    public function isUmum()
    {
        return $this->kategori === 'umum';
    }

    /**
     * Check if jurusan is khusus
     */
    public function isKhusus()
    {
        return $this->kategori === 'khusus';
    }

    /**
     * Get available kategori options
     */
    public static function getKategoriOptions()
    {
        return [
            'umum' => 'Umum',
            'khusus' => 'Khusus',
        ];
    }
}
