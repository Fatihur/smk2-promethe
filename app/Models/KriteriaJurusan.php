<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaJurusan extends Model
{
    use HasFactory;

    protected $table = 'kriteria_jurusan';

    protected $fillable = [
        'master_kriteria_id',
        'jurusan_id',
        'nilai_min',
        'nilai_max',
        'is_active',
    ];

    protected $casts = [
        'nilai_min' => 'decimal:2',
        'nilai_max' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the master kriteria that owns this record.
     */
    public function masterKriteria()
    {
        return $this->belongsTo(MasterKriteria::class);
    }

    /**
     * Get the jurusan that owns this record.
     */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Scope to get active kriteria jurusan
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get by jurusan
     */
    public function scopeByJurusan($query, $jurusanId)
    {
        return $query->where('jurusan_id', $jurusanId);
    }

    /**
     * Scope to get by master kriteria
     */
    public function scopeByMasterKriteria($query, $masterKriteriaId)
    {
        return $query->where('master_kriteria_id', $masterKriteriaId);
    }

    /**
     * Get the rentang nilai (range) for this kriteria
     */
    public function getRentangNilaiAttribute()
    {
        return $this->nilai_min . ' - ' . $this->nilai_max;
    }

    /**
     * Get the range width
     */
    public function getRangeWidthAttribute()
    {
        return $this->nilai_max - $this->nilai_min;
    }

    /**
     * Check if a nilai falls within the range
     */
    public function isNilaiInRange($nilai)
    {
        return $nilai >= $this->nilai_min && $nilai <= $this->nilai_max;
    }

    /**
     * Get all kriteria for a specific jurusan with their ranges
     */
    public static function getKriteriaForJurusan($jurusanId)
    {
        return static::with('masterKriteria')
                    ->where('jurusan_id', $jurusanId)
                    ->where('is_active', true)
                    ->orderBy('id')
                    ->get();
    }

    /**
     * Note: Method checkRentangOverlap dihapus karena rentang nilai boleh tumpang tindih.
     * Setiap kriteria memiliki sistem penilaian yang berbeda dan independen.
     */

    /**
     * Validate rentang nilai
     */
    public static function validateRentangNilai($nilaiMin, $nilaiMax)
    {
        $errors = [];

        if ($nilaiMin >= $nilaiMax) {
            $errors[] = 'Nilai minimum harus lebih kecil dari nilai maksimum.';
        }

        if ($nilaiMin < 0 || $nilaiMax < 0) {
            $errors[] = 'Nilai tidak boleh negatif.';
        }

        // Note: Batasan maksimum 100 dihapus karena setiap kriteria bisa memiliki skala yang berbeda
        // Misalnya: TPA (0-100), Psikologi (1-5), dll.

        return $errors;
    }
}
