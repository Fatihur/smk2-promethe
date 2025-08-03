<?php

namespace App\Exports;

use App\Services\StatusTrackingService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatistikJurusanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $tahunAkademikId;
    protected $statusService;

    public function __construct($tahunAkademikId)
    {
        $this->tahunAkademikId = $tahunAkademikId;
        $this->statusService = new StatusTrackingService();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $stats = $this->statusService->getDashboardStats($this->tahunAkademikId);
        return collect($stats['jurusan_stats']);
    }

    public function headings(): array
    {
        return [
            'Kode Jurusan',
            'Nama Jurusan',
            'Kuota',
            'Total Pendaftar',
            'Diterima',
            'Sisa Kuota',
            'Persentase Terisi (%)',
            'Status'
        ];
    }

    public function map($jurusanStat): array
    {
        return [
            $jurusanStat['jurusan']->kode_jurusan,
            $jurusanStat['jurusan']->nama_jurusan,
            $jurusanStat['kuota'],
            $jurusanStat['pendaftar'],
            $jurusanStat['diterima'],
            $jurusanStat['sisa_kuota'],
            $jurusanStat['persentase_terisi'],
            $jurusanStat['persentase_terisi'] >= 100 ? 'Penuh' : 'Tersedia'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Statistik Per Jurusan';
    }
}
