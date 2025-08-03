<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\TahunAkademik;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    private $tahunAkademik;
    private $jurusanMap;
    private $importedCount = 0;
    private $skippedCount = 0;

    public function __construct()
    {
        $this->tahunAkademik = TahunAkademik::where('is_active', true)->first();
        
        // Create mapping for jurusan by name and code
        $this->jurusanMap = Jurusan::where('is_active', true)->get()->mapWithKeys(function ($jurusan) {
            return [
                strtolower($jurusan->nama_jurusan) => $jurusan->id,
                strtolower($jurusan->kode_jurusan) => $jurusan->id,
            ];
        });
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows or rows with missing required fields
        if (empty($row['nisn']) || empty($row['nama_calon_peserta_didik']) || empty($row['pilihan_ke_1'])) {
            $this->skippedCount++;
            Log::info("Skipped row - Missing required fields", [
                'nisn' => $row['nisn'] ?? 'empty',
                'nama' => $row['nama_calon_peserta_didik'] ?? 'empty',
                'pilihan_1' => $row['pilihan_ke_1'] ?? 'empty'
            ]);
            return null;
        }

        // Validate NISN format (must be 10 digits)
        if (strlen($row['nisn']) !== 10 || !is_numeric($row['nisn'])) {
            $this->skippedCount++;
            Log::warning("Skipped row - Invalid NISN format", [
                'nisn' => $row['nisn'],
                'length' => strlen($row['nisn'])
            ]);
            return null;
        }

        try {
            // Parse tanggal lahir from "TEMPAT, TGL LAHIR" format
            $tempatTanggalLahir = $row['tempat_tgl_lahir'] ?? '';
            $tempatLahir = '';
            $tanggalLahir = null;

            if (!empty($tempatTanggalLahir)) {
                $parts = explode(',', $tempatTanggalLahir);
                $tempatLahir = trim($parts[0] ?? '');
                
                if (isset($parts[1])) {
                    $tanggalStr = trim($parts[1]);
                    try {
                        // Try different date formats
                        $tanggalLahir = Carbon::createFromFormat('d-m-Y', $tanggalStr)->format('Y-m-d');
                    } catch (\Exception $e) {
                        try {
                            $tanggalLahir = Carbon::createFromFormat('d/m/Y', $tanggalStr)->format('Y-m-d');
                        } catch (\Exception $e) {
                            try {
                                $tanggalLahir = Carbon::parse($tanggalStr)->format('Y-m-d');
                            } catch (\Exception $e) {
                                Log::warning("Could not parse date: $tanggalStr for NISN: " . $row['nisn']);
                                $tanggalLahir = '2008-01-01'; // Default date
                            }
                        }
                    }
                }
            }

            // Get jurusan IDs
            $pilihanJurusan1 = $this->getJurusanId($row['pilihan_ke_1'] ?? '');
            $pilihanJurusan2 = $this->getJurusanId($row['pilihan_ke_2'] ?? '');

            if (!$pilihanJurusan1) {
                Log::warning("Pilihan jurusan 1 tidak ditemukan: " . ($row['pilihan_ke_1'] ?? '') . " for NISN: " . $row['nisn']);
                $this->skippedCount++;
                return null;
            }

            // Generate nomor pendaftaran
            $noPendaftaran = $this->generateNoPendaftaran();

            // Determine kategori based on first choice
            $jurusan1 = Jurusan::find($pilihanJurusan1);
            $kategori = in_array($jurusan1->kode_jurusan, ['TAB', 'TSM']) ? 'khusus' : 'umum';

            // Determine gender from name (simple heuristic)
            $jenisKelamin = $this->determineGender($row['nama_calon_peserta_didik']);

            $siswa = Siswa::updateOrCreate(
                ['nisn' => $row['nisn']],
                [
                    'no_pendaftaran' => $noPendaftaran,
                    'nama_lengkap' => $row['nama_calon_peserta_didik'],
                    'jenis_kelamin' => $jenisKelamin,
                    'tempat_lahir' => $tempatLahir ?: 'Unknown',
                    'tanggal_lahir' => $tanggalLahir ?: '2008-01-01',
                    'alamat' => $row['alamat'] ?? 'Alamat tidak tersedia',
                    'nama_ayah' => $row['nama_ayah'] ?? null,
                    'asal_sekolah' => $row['asal_sekolah'] ?? 'Unknown',
                    'tahun_akademik_id' => $this->tahunAkademik->id,
                    'pilihan_jurusan_1' => $pilihanJurusan1,
                    'pilihan_jurusan_2' => $pilihanJurusan2,
                    'kategori' => $kategori,
                    'status_seleksi' => 'pending',
                ]
            );

            $this->importedCount++;
            return $siswa;

        } catch (\Exception $e) {
            Log::error("Error importing siswa with NISN: " . $row['nisn'] . " - " . $e->getMessage());
            $this->skippedCount++;
            return null;
        }
    }

    /**
     * Get jurusan ID by name or code
     */
    private function getJurusanId($jurusanName)
    {
        if (empty($jurusanName)) {
            return null;
        }

        $key = strtolower(trim($jurusanName));
        return $this->jurusanMap->get($key);
    }

    /**
     * Generate nomor pendaftaran
     */
    private function generateNoPendaftaran()
    {
        $year = date('Y');
        $lastNumber = Siswa::where('no_pendaftaran', 'like', $year . '%')
            ->orderBy('no_pendaftaran', 'desc')
            ->value('no_pendaftaran');

        if ($lastNumber) {
            $number = intval(substr($lastNumber, 4)) + 1;
        } else {
            $number = 1;
        }

        return $year . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Simple gender determination based on name
     */
    private function determineGender($name)
    {
        $femaleIndicators = ['siti', 'dewi', 'sri', 'nia', 'ani', 'rina', 'dina', 'maya', 'sari', 'putri'];
        $maleIndicators = ['ahmad', 'muhammad', 'budi', 'andi', 'dedi', 'rizki', 'agus', 'bambang'];

        $lowerName = strtolower($name);
        
        foreach ($femaleIndicators as $indicator) {
            if (strpos($lowerName, $indicator) !== false) {
                return 'P';
            }
        }
        
        foreach ($maleIndicators as $indicator) {
            if (strpos($lowerName, $indicator) !== false) {
                return 'L';
            }
        }

        // Default to male if can't determine
        return 'L';
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'nisn' => 'required|string|size:10',
            'nama_calon_peserta_didik' => 'required|string|max:255',
        ];
    }

    /**
     * Get import statistics
     */
    public function getImportStats()
    {
        return [
            'imported' => $this->importedCount,
            'skipped' => $this->skippedCount,
            'errors' => count($this->errors()),
            'failures' => count($this->failures()),
        ];
    }
}
