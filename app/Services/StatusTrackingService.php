<?php

namespace App\Services;

use App\Models\TahunAkademik;
use App\Models\Siswa;
use App\Models\PrometheusResult;
use App\Models\Jurusan;
use App\Models\User;

class StatusTrackingService
{
    /**
     * Get comprehensive dashboard statistics
     */
    public function getDashboardStats(?int $tahunAkademikId = null): array
    {
        if (!$tahunAkademikId) {
            $tahunAkademik = TahunAkademik::getActive();
            $tahunAkademikId = $tahunAkademik ? $tahunAkademik->id : null;
        }

        if (!$tahunAkademikId) {
            return $this->getEmptyStats();
        }

        return [
            'tahun_akademik' => TahunAkademik::find($tahunAkademikId),
            'siswa_stats' => $this->getSiswaStats($tahunAkademikId),
            'promethee_stats' => $this->getPrometheusStats($tahunAkademikId),
            'validation_stats' => $this->getValidationStats($tahunAkademikId),
            'jurusan_stats' => $this->getJurusanStats($tahunAkademikId),
            'process_status' => $this->getProcessStatus($tahunAkademikId),
        ];
    }

    /**
     * Get student statistics
     */
    private function getSiswaStats(int $tahunAkademikId): array
    {
        $total = Siswa::where('tahun_akademik_id', $tahunAkademikId)->count();
        $khusus = Siswa::where('tahun_akademik_id', $tahunAkademikId)->where('kategori', 'khusus')->count();
        $umum = Siswa::where('tahun_akademik_id', $tahunAkademikId)->where('kategori', 'umum')->count();
        
        $statusCounts = Siswa::where('tahun_akademik_id', $tahunAkademikId)
            ->selectRaw('status_seleksi, COUNT(*) as count')
            ->groupBy('status_seleksi')
            ->pluck('count', 'status_seleksi')
            ->toArray();

        return [
            'total' => $total,
            'khusus' => $khusus,
            'umum' => $umum,
            'pending' => $statusCounts['pending'] ?? 0,
            'lulus' => $statusCounts['lulus'] ?? 0,
            'lulus_pilihan_2' => $statusCounts['lulus_pilihan_2'] ?? 0,
            'tidak_lulus' => $statusCounts['tidak_lulus'] ?? 0,
        ];
    }

    /**
     * Get PROMETHEE statistics
     */
    private function getPrometheusStats(int $tahunAkademikId): array
    {
        $khususResults = PrometheusResult::where('tahun_akademik_id', $tahunAkademikId)
            ->where('kategori', 'khusus')
            ->count();

        $umumResults = PrometheusResult::where('tahun_akademik_id', $tahunAkademikId)
            ->where('kategori', 'umum')
            ->count();

        $khususInKuota = PrometheusResult::where('tahun_akademik_id', $tahunAkademikId)
            ->where('kategori', 'khusus')
            ->where('masuk_kuota', true)
            ->count();

        return [
            'khusus_processed' => $khususResults,
            'umum_processed' => $umumResults,
            'khusus_in_kuota' => $khususInKuota,
            'khusus_out_kuota' => $khususResults - $khususInKuota,
        ];
    }

    /**
     * Get validation statistics
     */
    private function getValidationStats(int $tahunAkademikId): array
    {
        $validationCounts = PrometheusResult::where('tahun_akademik_id', $tahunAkademikId)
            ->selectRaw('status_validasi, COUNT(*) as count')
            ->groupBy('status_validasi')
            ->pluck('count', 'status_validasi')
            ->toArray();

        return [
            'pending' => $validationCounts['pending'] ?? 0,
            'lulus' => $validationCounts['lulus'] ?? 0,
            'lulus_pilihan_2' => $validationCounts['lulus_pilihan_2'] ?? 0,
            'tidak_lulus' => $validationCounts['tidak_lulus'] ?? 0,
        ];
    }

    /**
     * Get jurusan statistics
     */
    private function getJurusanStats(int $tahunAkademikId): array
    {
        $jurusanStats = [];
        $jurusanList = Jurusan::active()->get();

        foreach ($jurusanList as $jurusan) {
            $pendaftar = Siswa::where('tahun_akademik_id', $tahunAkademikId)
                ->where('pilihan_jurusan_1', $jurusan->id)
                ->count();

            $diterima = Siswa::where('tahun_akademik_id', $tahunAkademikId)
                ->where('jurusan_diterima_id', $jurusan->id)
                ->count();

            $jurusanStats[] = [
                'jurusan' => $jurusan,
                'pendaftar' => $pendaftar,
                'diterima' => $diterima,
                'kuota' => $jurusan->kuota,
                'sisa_kuota' => max(0, $jurusan->kuota - $diterima),
                'persentase_terisi' => $jurusan->kuota > 0 ? round(($diterima / $jurusan->kuota) * 100, 1) : 0,
            ];
        }

        return $jurusanStats;
    }

    /**
     * Get process status
     */
    private function getProcessStatus(int $tahunAkademikId): array
    {
        $khususResults = PrometheusResult::where('tahun_akademik_id', $tahunAkademikId)
            ->where('kategori', 'khusus')
            ->exists();

        $umumResults = PrometheusResult::where('tahun_akademik_id', $tahunAkademikId)
            ->where('kategori', 'umum')
            ->exists();

        $khususValidated = PrometheusResult::where('tahun_akademik_id', $tahunAkademikId)
            ->where('kategori', 'khusus')
            ->where('status_validasi', '!=', 'pending')
            ->count();

        $khususTotal = PrometheusResult::where('tahun_akademik_id', $tahunAkademikId)
            ->where('kategori', 'khusus')
            ->count();

        $umumValidated = PrometheusResult::where('tahun_akademik_id', $tahunAkademikId)
            ->where('kategori', 'umum')
            ->where('status_validasi', '!=', 'pending')
            ->count();

        $umumTotal = PrometheusResult::where('tahun_akademik_id', $tahunAkademikId)
            ->where('kategori', 'umum')
            ->count();

        return [
            'khusus_promethee_done' => $khususResults,
            'umum_promethee_done' => $umumResults,
            'khusus_validation_progress' => $khususTotal > 0 ? round(($khususValidated / $khususTotal) * 100, 1) : 0,
            'umum_validation_progress' => $umumTotal > 0 ? round(($umumValidated / $umumTotal) * 100, 1) : 0,
            'overall_progress' => $this->calculateOverallProgress($tahunAkademikId),
        ];
    }

    /**
     * Calculate overall process progress
     */
    private function calculateOverallProgress(int $tahunAkademikId): array
    {
        $totalSiswa = Siswa::where('tahun_akademik_id', $tahunAkademikId)->count();
        
        if ($totalSiswa == 0) {
            return [
                'percentage' => 0,
                'stage' => 'Belum Dimulai',
                'processed' => 0,
                'total' => 0
            ];
        }

        $processedSiswa = Siswa::where('tahun_akademik_id', $tahunAkademikId)
            ->where('status_seleksi', '!=', 'pending')
            ->count();

        $percentage = round(($processedSiswa / $totalSiswa) * 100, 1);

        // Determine current stage
        $stage = 'Belum Dimulai';
        
        if ($percentage == 0) {
            $stage = 'Belum Dimulai';
        } elseif ($percentage < 50) {
            $stage = 'Proses Kategori Khusus';
        } elseif ($percentage < 100) {
            $stage = 'Proses Kategori Umum';
        } else {
            $stage = 'Selesai';
        }

        return [
            'percentage' => $percentage,
            'stage' => $stage,
            'processed' => $processedSiswa,
            'total' => $totalSiswa,
        ];
    }

    /**
     * Get empty statistics when no active academic year
     */
    private function getEmptyStats(): array
    {
        return [
            'tahun_akademik' => null,
            'siswa_stats' => [
                'total' => 0,
                'khusus' => 0,
                'umum' => 0,
                'pending' => 0,
                'lulus' => 0,
                'lulus_pilihan_2' => 0,
                'tidak_lulus' => 0,
            ],
            'promethee_stats' => [
                'khusus_processed' => 0,
                'umum_processed' => 0,
                'khusus_in_kuota' => 0,
                'khusus_out_kuota' => 0,
            ],
            'validation_stats' => [
                'pending' => 0,
                'lulus' => 0,
                'lulus_pilihan_2' => 0,
                'tidak_lulus' => 0,
            ],
            'jurusan_stats' => [],
            'process_status' => [
                'khusus_promethee_done' => false,
                'umum_promethee_done' => false,
                'khusus_validation_progress' => 0,
                'umum_validation_progress' => 0,
                'overall_progress' => [
                    'percentage' => 0,
                    'stage' => 'Belum Dimulai',
                    'total' => 0,
                    'processed' => 0
                ],
            ],
        ];
    }

    /**
     * Get statistics for specific ketua jurusan
     */
    public function getKetuaJurusanStats(User $user, ?int $tahunAkademikId = null): array
    {
        if (!$user->jurusan_id) {
            return ['error' => 'User tidak memiliki jurusan'];
        }

        if (!$tahunAkademikId) {
            $tahunAkademik = TahunAkademik::getActive();
            $tahunAkademikId = $tahunAkademik ? $tahunAkademik->id : null;
        }

        if (!$tahunAkademikId) {
            return ['error' => 'Tidak ada tahun akademik aktif'];
        }

        $pendingValidation = PrometheusResult::whereHas('siswa', function ($query) use ($user) {
                $query->where('pilihan_jurusan_1', $user->jurusan_id)
                      ->orWhere('jurusan_diterima_id', $user->jurusan_id);
            })
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->where('status_validasi', 'pending')
            ->count();

        $validated = PrometheusResult::whereHas('siswa', function ($query) use ($user) {
                $query->where('pilihan_jurusan_1', $user->jurusan_id)
                      ->orWhere('jurusan_diterima_id', $user->jurusan_id);
            })
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->where('status_validasi', '!=', 'pending')
            ->count();

        $totalPendaftar = Siswa::where('tahun_akademik_id', $tahunAkademikId)
            ->where('pilihan_jurusan_1', $user->jurusan_id)
            ->count();

        $diterima = Siswa::where('tahun_akademik_id', $tahunAkademikId)
            ->where('jurusan_diterima_id', $user->jurusan_id)
            ->count();

        return [
            'jurusan' => $user->jurusan,
            'pending_validation' => $pendingValidation,
            'validated' => $validated,
            'total_pendaftar' => $totalPendaftar,
            'diterima' => $diterima,
            'kuota' => $user->jurusan->kuota,
            'sisa_kuota' => max(0, $user->jurusan->kuota - $diterima),
        ];
    }
}
