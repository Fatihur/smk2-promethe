<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSiswa extends Model
{
    use HasFactory;

    protected $table = 'nilai_siswa';

    protected $fillable = [
        'siswa_id',
        'master_kriteria_id',
        'nilai',
        'keterangan',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    /**
     * Get the siswa that owns the nilai.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Get the master kriteria that owns the nilai.
     */
    public function masterKriteria()
    {
        return $this->belongsTo(MasterKriteria::class);
    }

    /**
     * Alias for masterKriteria relationship for backward compatibility.
     */
    public function kriteria()
    {
        return $this->masterKriteria();
    }
}
