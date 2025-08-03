<?php

namespace App\Exports;

use App\Models\Jurusan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class SiswaTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Template' => new SiswaTemplateSheet(),
            'Daftar Jurusan' => new JurusanListSheet(),
        ];
    }
}

class SiswaTemplateSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'Teknik Alat Berat',
                'Teknik Sepeda Motor',
                '1234567890',
                'Ahmad Budi Santoso',
                'Jakarta, 15-05-2008',
                'SMP Negeri 1 Jakarta',
                'Budi Santoso',
                'Jl. Merdeka No. 123, Jakarta'
            ],
            [
                'Teknik Sepeda Motor',
                '', // pilihan ke-2 kosong
                '1234567891',
                'Siti Dewi Sari',
                'Bandung, 20-03-2008',
                'SMP Negeri 2 Bandung',
                'Agus Sari',
                'Jl. Sudirman No. 456, Bandung'
            ],
            [
                'TAB',
                'TSM',
                '1234567892',
                'Muhammad Rizki Pratama',
                'Surabaya, 10-07-2008',
                'SMP Negeri 3 Surabaya',
                'Rizki Wijaya',
                'Jl. Pahlawan No. 789, Surabaya'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'pilihan_ke_1',
            'pilihan_ke_2',
            'nisn',
            'nama_calon_peserta_didik',
            'tempat_tgl_lahir',
            'asal_sekolah',
            'nama_ayah',
            'alamat'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold with background color
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ]
            ],
            // Add borders to all cells
            'A1:H4' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}

class JurusanListSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        $jurusan = Jurusan::where('is_active', true)->get();
        $data = [];
        
        foreach ($jurusan as $j) {
            $data[] = [
                $j->kode_jurusan,
                $j->nama_jurusan,
                ucfirst($j->kategori),
                $j->kuota
            ];
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'Kode Jurusan',
            'Nama Jurusan',
            'Kategori',
            'Kuota'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold with background color
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '70AD47']
                ]
            ],
        ];
    }
}
