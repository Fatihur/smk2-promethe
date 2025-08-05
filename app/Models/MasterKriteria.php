<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKriteria extends Model
{
    use HasFactory;

    protected $table = 'master_kriteria';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'deskripsi',
        'tipe',
        'bobot',
        'nilai_min',
        'nilai_max',
        'is_active',
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'nilai_min' => 'decimal:2',
        'nilai_max' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the nilai siswa for the kriteria.
     */

    /**
     * Get the nilai siswa for the kriteria.
     */
    public function nilaiSiswa()
    {
        return $this->hasMany(NilaiSiswa::class);
    }

    /**
     * Scope to get active kriteria
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get benefit type kriteria
     */
    public function scopeBenefit($query)
    {
        return $query->where('tipe', 'benefit');
    }

    /**
     * Scope to get cost type kriteria
     */
    public function scopeCost($query)
    {
        return $query->where('tipe', 'cost');
    }

    /**
     * Get the tipe label for display
     */
    public function getTipeLabelAttribute()
    {
        return $this->tipe === 'benefit' ? 'Benefit' : 'Cost';
    }

    /**
     * Check if kriteria is benefit type
     */
    public function isBenefit()
    {
        return $this->tipe === 'benefit';
    }

    /**
     * Check if kriteria is cost type
     */
    public function isCost()
    {
        return $this->tipe === 'cost';
    }

    /**
     * Get formatted bobot for display
     */
    public function getBobotFormattedAttribute()
    {
        return number_format($this->bobot, 2);
    }

    /**
     * Get nilai range as string
     */
    public function getNilaiRangeAttribute()
    {
        return $this->nilai_min . ' - ' . $this->nilai_max;
    }

    /**
     * Validate if a nilai is within range
     */
    public function isNilaiValid($nilai)
    {
        return $nilai >= $this->nilai_min && $nilai <= $this->nilai_max;
    }

    /**
     * Get all active kriteria with their weights
     */
    public static function getActiveWithWeights()
    {
        return static::where('is_active', true)
                    ->orderBy('kode_kriteria')
                    ->get();
    }
}
