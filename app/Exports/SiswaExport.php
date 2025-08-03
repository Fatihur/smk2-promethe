<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\TahunAkademik;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $tahunAkademikId;
    private $kategori;
    private $jurusanId;

    public function __construct($tahunAkademikId = null, $kategori = 'all', $jurusanId = null)
    {
        $this->tahunAkademikId = $tahunAkademikId ?: TahunAkademik::where('is_active', true)->value('id');
        $this->kategori = $kategori;
        $this->jurusanId = $jurusanId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Siswa::with(['pilihanJurusan1', 'pilihanJurusan2', 'tahunAkademik'])
            ->where('tahun_akademik_id', $this->tahunAkademikId);

        if ($this->kategori !== 'all') {
            $query->where('kategori', $this->kategori);
        }

        if ($this->jurusanId) {
            $query->where('pilihan_jurusan_1', $this->jurusanId);
        }

        return $query->orderBy('no_pendaftaran')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Email',
            'Nama Ayah',
            'Asal Sekolah',
            'Pilihan Jurusan 1',
            'Pilihan Jurusan 2',
            'Kategori',
            'Status',
            'Status Seleksi',
            'Tahun Akademik',
        ];
    }

    /**
     * @param mixed $siswa
     * @return array
     */
    public function map($siswa): array
    {
        return [
            $siswa->no_pendaftaran,
            $siswa->nisn,
            $siswa->nama_lengkap,
            $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d-m-Y') : '',
            $siswa->alamat,
            $siswa->email,
            $siswa->nama_ayah,
            $siswa->asal_sekolah,
            $siswa->pilihanJurusan1 ? $siswa->pilihanJurusan1->nama_jurusan : '',
            $siswa->pilihanJurusan2 ? $siswa->pilihanJurusan2->nama_jurusan : '',
            ucfirst($siswa->kategori),
            ucfirst($siswa->status ?? 'pending'),
            ucfirst(str_replace('_', ' ', $siswa->status_seleksi)),
            $siswa->tahunAkademik ? $siswa->tahunAkademik->tahun_akademik : '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
