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
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the jurusan that use this kriteria with their weights.
     */
    public function jurusans()
    {
        return $this->belongsToMany(Jurusan::class, 'kriteria_jurusan')
                    ->withPivot('bobot', 'is_active')
                    ->withTimestamps();
    }

    /**
     * Get the kriteria jurusan pivot records.
     */
    public function kriteriaJurusan()
    {
        return $this->hasMany(KriteriaJurusan::class);
    }

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
     * Get weight for specific jurusan
     */
    public function getBobotForJurusan($jurusanId)
    {
        $kriteriaJurusan = $this->kriteriaJurusan()
                                ->where('jurusan_id', $jurusanId)
                                ->first();
        
        return $kriteriaJurusan ? $kriteriaJurusan->bobot : 0;
    }

    /**
     * Check if kriteria is active for specific jurusan
     */
    public function isActiveForJurusan($jurusanId)
    {
        $kriteriaJurusan = $this->kriteriaJurusan()
                                ->where('jurusan_id', $jurusanId)
                                ->first();
        
        return $kriteriaJurusan ? $kriteriaJurusan->is_active : false;
    }
}
