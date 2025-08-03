<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrometheusResult extends Model
{
    use HasFactory;

    protected $table = 'promethee_results';

    protected $fillable = [
        'siswa_id',
        'tahun_akademik_id',
        'kategori',
        'phi_plus',
        'phi_minus',
        'phi_net',
        'ranking',
        'masuk_kuota',
        'status_validasi',
        'validated_by',
        'validated_at',
        'catatan_validasi',
    ];

    protected $casts = [
        'phi_plus' => 'decimal:6',
        'phi_minus' => 'decimal:6',
        'phi_net' => 'decimal:6',
        'masuk_kuota' => 'boolean',
        'validated_at' => 'datetime',
    ];

    /**
     * Get the siswa that owns the result.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Get the tahun akademik that owns the result.
     */
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    /**
     * Get the user who validated this result.
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Scope to get results by kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope to get results that are in kuota
     */
    public function scopeInKuota($query)
    {
        return $query->where('masuk_kuota', true);
    }

    /**
     * Scope to get results by validation status
     */
    public function scopeByValidationStatus($query, $status)
    {
        return $query->where('status_validasi', $status);
    }

    /**
     * Check if result is validated
     */
    public function isValidated(): bool
    {
        return $this->status_validasi !== 'pending';
    }

    /**
     * Check if result is passed
     */
    public function isPassed(): bool
    {
        return in_array($this->status_validasi, ['lulus', 'lulus_pilihan_2']);
    }
}
