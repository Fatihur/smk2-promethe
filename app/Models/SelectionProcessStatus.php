<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectionProcessStatus extends Model
{
    use HasFactory;

    protected $table = 'selection_process_status';

    protected $fillable = [
        'tahun_akademik_id',
        'kategori_khusus_status',
        'kategori_umum_status',
        'kuota_khusus',
        'khusus_started_at',
        'khusus_completed_at',
        'umum_started_at',
        'umum_completed_at',
    ];

    protected $casts = [
        'khusus_started_at' => 'datetime',
        'khusus_completed_at' => 'datetime',
        'umum_started_at' => 'datetime',
        'umum_completed_at' => 'datetime',
    ];

    /**
     * Get the tahun akademik that owns the selection process status.
     */
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }
}
