<?php

namespace App\Exports;

use App\Models\PrometheusResult;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RankingExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $tahunAkademikId;
    protected $kategori;

    public function __construct($tahunAkademikId, $kategori = 'all')
    {
        $this->tahunAkademikId = $tahunAkademikId;
        $this->kategori = $kategori;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = PrometheusResult::with(['siswa.pilihanJurusan1', 'siswa.pilihanJurusan2', 'siswa.jurusanDiterima'])
            ->where('tahun_akademik_id', $this->tahunAkademikId);

        if ($this->kategori !== 'all') {
            $query->where('kategori', $this->kategori);
        }

        return $query->orderBy('kategori')
                    ->orderBy('ranking')
                    ->get();
    }

    public function headings(): array
    {
        return [
            'Ranking',
            'No. Pendaftaran',
            'NISN',
            'Nama Lengkap',
            'Kategori',
            'Pilihan Jurusan 1',
            'Pilihan Jurusan 2',
            'Phi Net Score',
            'Status Seleksi',
            'Jurusan Diterima'
        ];
    }

    public function map($result): array
    {
        return [
            $result->ranking,
            $result->siswa->no_pendaftaran,
            $result->siswa->nisn,
            $result->siswa->nama_lengkap,
            ucfirst($result->kategori),
            $result->siswa->pilihanJurusan1->nama_jurusan ?? '-',
            $result->siswa->pilihanJurusan2->nama_jurusan ?? '-',
            number_format($result->phi_net, 4),
            $this->getStatusLabel($result->siswa->status_seleksi),
            $result->siswa->jurusanDiterima->nama_jurusan ?? '-'
        ];
    }

    private function getStatusLabel($status)
    {
        switch ($status) {
            case 'lulus':
                return 'Lulus';
            case 'tidak_lulus':
                return 'Tidak Lulus';
            case 'lulus_pilihan_2':
                return 'Lulus Pilihan 2';
            case 'pending':
                return 'Pending';
            default:
                return 'Belum Diproses';
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
            
            // Set background color for header
            'A1:J1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'],
                    'bold' => true
                ]
            ]
        ];
    }

    public function title(): string
    {
        $kategoriLabel = $this->kategori === 'all' ? 'Semua Kategori' : ucfirst($this->kategori);
        return 'Ranking Siswa - ' . $kategoriLabel;
    }
}
