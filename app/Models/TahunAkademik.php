<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $table = 'tahun_akademik';

    protected $fillable = [
        'tahun',
        'semester',
        'is_active',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get the siswa for the tahun akademik.
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    /**
     * Get the selection process status for this tahun akademik.
     */
    public function selectionProcessStatus()
    {
        return $this->hasOne(SelectionProcessStatus::class);
    }

    /**
     * Scope to get active tahun akademik
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the active tahun akademik
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Set this tahun akademik as active and deactivate others
     */
    public function setAsActive()
    {
        DB::transaction(function () {
            // First, deactivate all tahun akademik
            static::query()->update(['is_active' => false]);

            // Then activate this one
            $this->update(['is_active' => true]);
        });
    }
}
