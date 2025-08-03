<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HasilSeleksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $tahunAkademikId;
    protected $kategori;
    protected $jurusanId;

    public function __construct($tahunAkademikId, $kategori = 'all', $jurusanId = null)
    {
        $this->tahunAkademikId = $tahunAkademikId;
        $this->kategori = $kategori;
        $this->jurusanId = $jurusanId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Siswa::with(['pilihanJurusan1', 'pilihanJurusan2', 'jurusanDiterima', 'prometheusResults'])
            ->where('tahun_akademik_id', $this->tahunAkademikId);

        if ($this->kategori !== 'all') {
            $query->where('kategori', $this->kategori);
        }

        if ($this->jurusanId) {
            $query->where(function($q) {
                $q->where('pilihan_jurusan_1', $this->jurusanId)
                  ->orWhere('jurusan_diterima_id', $this->jurusanId);
            });
        }

        return $query->orderBy('no_pendaftaran')->get();
    }

    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Asal Sekolah',
            'Pilihan Jurusan 1',
            'Pilihan Jurusan 2',
            'Kategori',
            'Status Seleksi',
            'Jurusan Diterima',
            'Ranking PROMETHEE',
            'Phi Net Score'
        ];
    }

    public function map($siswa): array
    {
        $prometheusResult = $siswa->prometheusResults->first();

        return [
            $siswa->no_pendaftaran,
            $siswa->nisn,
            $siswa->nama_lengkap,
            $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir->format('d/m/Y'),
            $siswa->asal_sekolah,
            $siswa->pilihanJurusan1->nama_jurusan ?? '-',
            $siswa->pilihanJurusan2->nama_jurusan ?? '-',
            ucfirst($siswa->kategori),
            $this->getStatusLabel($siswa->status_seleksi),
            $siswa->jurusanDiterima->nama_jurusan ?? '-',
            $prometheusResult ? $prometheusResult->ranking : '-',
            $prometheusResult ? number_format($prometheusResult->phi_net, 4) : '-'
        ];
    }

    private function getStatusLabel($status): string
    {
        return match($status) {
            'pending' => 'Pending',
            'lulus' => 'Lulus',
            'lulus_pilihan_2' => 'Lulus Pilihan 2',
            'tidak_lulus' => 'Tidak Lulus',
            default => $status
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Hasil Seleksi PPDB';
    }
}
