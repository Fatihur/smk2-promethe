<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\KriteriaJurusan;
use App\Models\NilaiSiswa;
use App\Models\PrometheusResult;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\DB;

class PrometheusService
{
    /**
     * Calculate PROMETHEE ranking for given category
     *
     * @param string $kategori 'khusus' or 'umum'
     * @param int|null $tahunAkademikId
     * @param int|null $kuota
     * @param int|null $jurusanId
     * @return array
     */
    public function calculateRanking(string $kategori, int $tahunAkademikId = null, int $kuota = null, int $jurusanId = null): array
    {
        // Get active tahun akademik if not provided
        if (!$tahunAkademikId) {
            $tahunAkademik = TahunAkademik::getActive();
            $tahunAkademikId = $tahunAkademik->id;
        }

        // Get siswa for the category
        $siswa = Siswa::where('kategori', $kategori)
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->with(['nilaiSiswa.masterKriteria'])
            ->get();

        if ($siswa->isEmpty()) {
            return ['error' => 'Tidak ada siswa dalam kategori ' . $kategori];
        }

        if ($siswa->count() < 2) {
            return ['error' => 'PROMETHEE memerlukan minimal 2 siswa untuk perbandingan'];
        }

        // Get first siswa's jurusan to determine kriteria weights
        $firstSiswa = $siswa->first();
        if (!$firstSiswa || !$firstSiswa->pilihan_jurusan_1) {
            return ['error' => 'Tidak dapat menentukan jurusan untuk kriteria'];
        }

        // Get active kriteria jurusan with weights
        $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $firstSiswa->pilihan_jurusan_1)
            ->where('is_active', true)
            ->with('masterKriteria')
            ->get();

        if ($kriteriaJurusan->isEmpty()) {
            return ['error' => 'Tidak ada kriteria aktif untuk jurusan ini'];
        }

        // Validate that all siswa have complete nilai
        foreach ($siswa as $s) {
            $nilaiCount = $s->nilaiSiswa->count();
            if ($nilaiCount < $kriteriaJurusan->count()) {
                return ['error' => "Siswa {$s->nama_lengkap} belum memiliki nilai lengkap"];
            }
        }

        /*
         * LANGKAH-LANGKAH PERHITUNGAN PROMETHEE (Preferensi Usual)
         *
         * 1. Siapkan Data Alternatif (Siswa) dan Nilai Kriterianya
         * 2. Tentukan Bobot Kriteria (equal weights dalam implementasi ini)
         * 3. Hitung Selisih Antar Alternatif: d_k(A,B) = nilai_kriteria_A - nilai_kriteria_B
         * 4. Hitung Nilai Preferensi per Kriteria: P(d) = 1 if d > 0, else P(d) = 0
         * 5. Hitung Indeks Preferensi Global: π(A,B) = Σ [w_k × P_k(A,B)]
         * 6. Hitung Leaving Flow (φ⁺) dan Entering Flow (φ⁻)
         * 7. Hitung Net Flow (φ): φ(A) = φ⁺(A) - φ⁻(A)
         * 8. Ranking Alternatif: Urutkan berdasarkan nilai Net Flow tertinggi ke terendah
         */

        // Step 1: Build decision matrix (Data Alternatif dan Nilai Kriteria)
        $decisionMatrix = $this->buildDecisionMatrix($siswa, $kriteriaJurusan->pluck('masterKriteria'));

        // Steps 2-5: Calculate preference matrix (Bobot, Selisih, Preferensi, Indeks Global)
        $preferenceMatrix = $this->calculatePreferenceMatrix($decisionMatrix, $kriteriaJurusan);

        // Steps 6-7: Calculate outranking flows (Leaving Flow, Entering Flow, Net Flow)
        $flows = $this->calculateOutrankingFlows($preferenceMatrix, $siswa->count());

        // Step 8: Calculate net flows and ranking (Ranking berdasarkan Net Flow)
        $results = $this->calculateNetFlowsAndRanking($flows, $siswa, $kuota);

        // Save results to database
        $this->saveResults($results, $kategori, $tahunAkademikId);

        return [
            'success' => true,
            'kategori' => $kategori,
            'total_siswa' => $siswa->count(),
            'kuota' => $kuota,
            'results' => $results
        ];
    }

    /**
     * Build decision matrix from siswa nilai
     */
    private function buildDecisionMatrix($siswa, $kriteria): array
    {
        $matrix = [];

        foreach ($siswa as $index => $s) {
            $matrix[$index] = [];
            foreach ($kriteria as $k) {
                $nilai = $s->nilaiSiswa->where('master_kriteria_id', $k->id)->first();
                $matrix[$index][$k->id] = $nilai ? $nilai->nilai : 0;
            }
        }

        return $matrix;
    }

    /**
     * Calculate preference matrix using usual preference function
     * Following PROMETHEE steps with Usual preference function
     */
    private function calculatePreferenceMatrix($decisionMatrix, $kriteriaJurusan): array
    {
        $n = count($decisionMatrix);
        $preferenceMatrix = [];

        // Initialize preference matrix
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $preferenceMatrix[$i][$j] = 0;
            }
        }

        // Define weights for each criteria (Step 2: Tentukan Bobot Kriteria)
        // Since we don't have bobot field anymore, we'll use equal weights
        // or calculate based on range width as importance indicator
        $weights = $this->calculateCriteriaWeights($kriteriaJurusan);

        // Step 3-5: Calculate preferences for each pair of alternatives
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i != $j) {
                    $globalPreference = 0;

                    foreach ($kriteriaJurusan as $kj) {
                        $k = $kj->masterKriteria;
                        $weight = $weights[$k->id];

                        // Step 3: Hitung Selisih Antar Alternatif
                        // d_k(A,B) = nilai_kriteria_A - nilai_kriteria_B
                        $diff = $decisionMatrix[$i][$k->id] - $decisionMatrix[$j][$k->id];

                        // For cost criteria, reverse the difference
                        if ($k->tipe === 'cost') {
                            $diff = -$diff;
                        }

                        // Step 4: Hitung Nilai Preferensi per Kriteria
                        // Usual preference function: P(d) = 1 if d > 0, else P(d) = 0
                        $preference = $diff > 0 ? 1 : 0;

                        // Step 5: Hitung Indeks Preferensi Global
                        // π(A,B) = Σ [w_k × P_k(A,B)]
                        $globalPreference += $weight * $preference;
                    }

                    $preferenceMatrix[$i][$j] = $globalPreference;
                }
            }
        }

        return $preferenceMatrix;
    }

    /**
     * Calculate criteria weights
     * Step 2: Tentukan Bobot Kriteria
     *
     * Since bobot field is removed, we use equal weights by default
     * This can be modified to use predefined weights or range-based weights
     */
    private function calculateCriteriaWeights($kriteriaJurusan): array
    {
        $weights = [];
        $totalKriteria = $kriteriaJurusan->count();

        // Option 1: Equal weights (current implementation)
        foreach ($kriteriaJurusan as $kj) {
            $weights[$kj->masterKriteria->id] = 1.0 / $totalKriteria;
        }

        // Option 2: Predefined weights based on criteria type (can be uncommented if needed)
        /*
        foreach ($kriteriaJurusan as $kj) {
            $kode = $kj->masterKriteria->kode_kriteria;
            switch ($kode) {
                case 'TPA':
                    $weights[$kj->masterKriteria->id] = 0.5;  // 50%
                    break;
                case 'PSI':
                    $weights[$kj->masterKriteria->id] = 0.25; // 25%
                    break;
                case 'MNT':
                    $weights[$kj->masterKriteria->id] = 0.25; // 25%
                    break;
                default:
                    $weights[$kj->masterKriteria->id] = 1.0 / $totalKriteria;
            }
        }
        */

        return $weights;
    }

    /**
     * Calculate outranking flows (phi+ and phi-)
     * Step 6: Hitung Leaving Flow (φ⁺) dan Entering Flow (φ⁻)
     */
    private function calculateOutrankingFlows($preferenceMatrix, $n): array
    {
        $flows = [];

        // Prevent division by zero
        if ($n <= 1) {
            return [];
        }

        for ($i = 0; $i < $n; $i++) {
            $phiPlus = 0;  // Leaving Flow (φ⁺) - Outranking flow
            $phiMinus = 0; // Entering Flow (φ⁻) - Outranked flow

            for ($j = 0; $j < $n; $j++) {
                if ($i != $j) {
                    // φ⁺(A) = (1 / (n - 1)) × Σ π(A, j)
                    $phiPlus += $preferenceMatrix[$i][$j];

                    // φ⁻(A) = (1 / (n - 1)) × Σ π(j, A)
                    $phiMinus += $preferenceMatrix[$j][$i];
                }
            }

            // Step 6: Calculate final flows
            $phiPlusNormalized = $phiPlus / ($n - 1);
            $phiMinusNormalized = $phiMinus / ($n - 1);

            $flows[$i] = [
                'phi_plus' => $phiPlusNormalized,   // Leaving Flow
                'phi_minus' => $phiMinusNormalized, // Entering Flow
                'phi_net' => $phiPlusNormalized - $phiMinusNormalized  // Step 7: Net Flow
            ];
        }

        return $flows;
    }

    /**
     * Calculate net flows and create ranking
     * Step 8: Ranking Alternatif berdasarkan Net Flow tertinggi ke terendah
     */
    private function calculateNetFlowsAndRanking($flows, $siswa, $kuota): array
    {
        $results = [];
        $siswaArray = $siswa->toArray();

        // Combine flows with siswa data
        foreach ($flows as $index => $flow) {
            $results[] = [
                'siswa' => $siswaArray[$index],
                'phi_plus' => $flow['phi_plus'],   // Leaving Flow (φ⁺)
                'phi_minus' => $flow['phi_minus'], // Entering Flow (φ⁻)
                'phi_net' => $flow['phi_net']      // Net Flow (φ) = φ⁺ - φ⁻
            ];
        }

        // Step 8: Urutkan berdasarkan nilai Net Flow tertinggi ke terendah
        usort($results, function ($a, $b) {
            // Sort by phi_net descending (highest to lowest)
            return $b['phi_net'] <=> $a['phi_net'];
        });

        // Add ranking and kuota status
        foreach ($results as $index => &$result) {
            $result['ranking'] = $index + 1;  // Ranking starts from 1
            $result['masuk_kuota'] = $kuota ? ($index + 1 <= $kuota) : false;
        }

        return $results;
    }

    /**
     * Save PROMETHEE results to database
     */
    private function saveResults($results, $kategori, $tahunAkademikId): void
    {
        // Clear existing results for this category and tahun akademik
        PrometheusResult::where('kategori', $kategori)
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->delete();

        // Save new results
        foreach ($results as $result) {
            PrometheusResult::create([
                'siswa_id' => $result['siswa']['id'],
                'tahun_akademik_id' => $tahunAkademikId,
                'kategori' => $kategori,
                'phi_plus' => $result['phi_plus'],
                'phi_minus' => $result['phi_minus'],
                'phi_net' => $result['phi_net'],
                'ranking' => $result['ranking'],
                'masuk_kuota' => $result['masuk_kuota'],
                'status_validasi' => 'pending'
            ]);
        }
    }

    /**
     * Get PROMETHEE results for a category
     */
    public function getResults(string $kategori, int $tahunAkademikId = null): array
    {
        if (!$tahunAkademikId) {
            $tahunAkademik = TahunAkademik::getActive();
            $tahunAkademikId = $tahunAkademik->id;
        }

        $results = PrometheusResult::where('kategori', $kategori)
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->with(['siswa.pilihanJurusan1', 'siswa.pilihanJurusan2'])
            ->orderBy('ranking')
            ->get();

        return $results->toArray();
    }

    /**
     * Transfer failed Khusus students to Umum category
     */
    public function transferFailedKhususToUmum(int $tahunAkademikId = null): array
    {
        if (!$tahunAkademikId) {
            $tahunAkademik = TahunAkademik::getActive();
            $tahunAkademikId = $tahunAkademik->id;
        }

        // Get failed Khusus students (not in kuota)
        $failedStudents = PrometheusResult::where('kategori', 'khusus')
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->where('masuk_kuota', false)
            ->with('siswa')
            ->get();

        $transferredCount = 0;

        foreach ($failedStudents as $result) {
            $siswa = $result->siswa;
            
            // Update siswa category to umum and set pilihan_jurusan_2 as primary
            if ($siswa->pilihan_jurusan_2) {
                $siswa->update([
                    'kategori' => 'umum',
                    'pilihan_jurusan_1' => $siswa->pilihan_jurusan_2,
                    'pilihan_jurusan_2' => null
                ]);
                $transferredCount++;
            }
        }

        return [
            'success' => true,
            'transferred_count' => $transferredCount,
            'message' => "Berhasil memindahkan {$transferredCount} siswa dari kategori Khusus ke Umum"
        ];
    }
}
