# Implementasi PROMETHEE (Preference Ranking Organization Method for Enrichment Evaluation)

## Overview
Sistem ini mengimplementasi metode PROMETHEE dengan fungsi preferensi **Usual** untuk ranking siswa dalam proses seleksi PPDB.

## Langkah-Langkah Perhitungan PROMETHEE

### 1. Siapkan Data Alternatif (Siswa) dan Nilai Kriterianya
**Implementasi:** `buildDecisionMatrix()`
- Mengambil data siswa dan nilai kriteria dari database
- Membuat matriks keputusan dengan siswa sebagai baris dan kriteria sebagai kolom
- Contoh kriteria: TPA (0–100), Psikotes (1–5), Minat & Bakat (0 atau 1)

```php
$decisionMatrix = [
    [siswa_1] => [kriteria_1 => nilai_1, kriteria_2 => nilai_2, ...],
    [siswa_2] => [kriteria_1 => nilai_1, kriteria_2 => nilai_2, ...],
    ...
];
```

### 2. Tentukan Bobot Kriteria
**Implementasi:** `calculateCriteriaWeights()`
- Saat ini menggunakan bobot sama rata (equal weights)
- Dapat dimodifikasi untuk menggunakan bobot khusus:
  - TPA: 0.5 (50%)
  - Psikotes: 0.25 (25%)
  - Minat & Bakat: 0.25 (25%)

```php
$weights = [
    'kriteria_id_1' => 0.33, // Equal weight
    'kriteria_id_2' => 0.33, // Equal weight
    'kriteria_id_3' => 0.34, // Equal weight
];
```

### 3. Hitung Selisih Antar Alternatif
**Implementasi:** Dalam `calculatePreferenceMatrix()`
- Untuk setiap pasang siswa A dan B, hitung: `d_k(A,B) = nilai_kriteria_A - nilai_kriteria_B`
- Untuk kriteria cost, balik selisihnya: `d_k(A,B) = nilai_kriteria_B - nilai_kriteria_A`

```php
$diff = $decisionMatrix[$i][$k->id] - $decisionMatrix[$j][$k->id];
if ($k->tipe === 'cost') {
    $diff = -$diff;
}
```

### 4. Hitung Nilai Preferensi per Kriteria
**Implementasi:** Fungsi preferensi **Usual**
- Jika d > 0 → P(d) = 1
- Jika d ≤ 0 → P(d) = 0

```php
$preference = $diff > 0 ? 1 : 0;
```

### 5. Hitung Indeks Preferensi Global
**Implementasi:** Dalam `calculatePreferenceMatrix()`
- π(A,B) = Σ [w_k × P_k(A,B)] untuk semua kriteria

```php
$globalPreference += $weight * $preference;
```

### 6. Hitung Leaving Flow (φ⁺) dan Entering Flow (φ⁻)
**Implementasi:** `calculateOutrankingFlows()`
- **Leaving Flow:** φ⁺(A) = (1 / (n - 1)) × Σ π(A, j)
- **Entering Flow:** φ⁻(A) = (1 / (n - 1)) × Σ π(j, A)

```php
$phiPlusNormalized = $phiPlus / ($n - 1);   // Leaving Flow
$phiMinusNormalized = $phiMinus / ($n - 1); // Entering Flow
```

### 7. Hitung Net Flow (φ)
**Implementasi:** Dalam `calculateOutrankingFlows()`
- φ(A) = φ⁺(A) - φ⁻(A)

```php
$phi_net = $phiPlusNormalized - $phiMinusNormalized;
```

### 8. Ranking Alternatif
**Implementasi:** `calculateNetFlowsAndRanking()`
- Urutkan berdasarkan nilai Net Flow tertinggi ke terendah

```php
usort($results, function ($a, $b) {
    return $b['phi_net'] <=> $a['phi_net']; // Descending order
});
```

## Struktur Data Output

### Hasil Perhitungan
```php
[
    'siswa' => [...],           // Data siswa
    'phi_plus' => 0.6667,       // Leaving Flow (φ⁺)
    'phi_minus' => 0.3333,      // Entering Flow (φ⁻)
    'phi_net' => 0.3334,        // Net Flow (φ)
    'ranking' => 1,             // Peringkat (1 = tertinggi)
    'masuk_kuota' => true       // Status kuota
]
```

### Database Storage
Data disimpan dalam tabel `promethee_results`:
- `phi_plus`: Leaving Flow
- `phi_minus`: Entering Flow  
- `phi_net`: Net Flow
- `ranking`: Peringkat berdasarkan Net Flow
- `masuk_kuota`: Status dalam kuota atau tidak
- `status_validasi`: Status validasi ketua jurusan

## Contoh Perhitungan

### Data Input
```
Siswa A: TPA=80, PSI=4, MNT=1
Siswa B: TPA=70, PSI=5, MNT=0
Siswa C: TPA=90, PSI=3, MNT=1
```

### Bobot Kriteria
```
TPA: 0.33, PSI: 0.33, MNT: 0.34
```

### Matriks Preferensi
```
     A    B    C
A    0   0.67  0.34
B   0.33  0   0.33
C   0.66 0.67   0
```

### Flows
```
Siswa A: φ⁺=0.505, φ⁻=0.495, φ=0.010
Siswa B: φ⁺=0.330, φ⁻=0.670, φ=-0.340
Siswa C: φ⁺=0.665, φ⁻=0.335, φ=0.330
```

### Ranking Final
```
1. Siswa C (φ=0.330)
2. Siswa A (φ=0.010)
3. Siswa B (φ=-0.340)
```

## Konfigurasi Bobot

### Opsi 1: Equal Weights (Default)
Semua kriteria memiliki bobot yang sama.

### Opsi 2: Custom Weights
Dapat diaktifkan dengan mengubah kode di `calculateCriteriaWeights()`:

```php
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
}
```

## Validasi dan Error Handling

### Validasi Input
- Minimal 2 siswa untuk perbandingan
- Semua siswa harus memiliki nilai lengkap untuk semua kriteria
- Kriteria harus aktif dan memiliki bobot

### Error Messages
- "Tidak ada siswa dalam kategori X"
- "PROMETHEE memerlukan minimal 2 siswa untuk perbandingan"
- "Siswa X belum memiliki nilai lengkap"
- "Tidak ada kriteria aktif untuk jurusan ini"

## Integrasi dengan Sistem

### Flow Proses
1. **Input:** Data siswa dan nilai kriteria
2. **Proses:** Perhitungan PROMETHEE 8 langkah
3. **Output:** Ranking dan status kuota
4. **Validasi:** Ketua jurusan memvalidasi hasil
5. **Final:** Status penerimaan siswa

### Database Integration
- Input: `siswa`, `nilai_siswa`, `kriteria_jurusan`
- Output: `promethee_results`
- Validation: `status_validasi`, `validated_by`, `validated_at`
