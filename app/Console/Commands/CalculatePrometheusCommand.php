<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PrometheusService;
use App\Models\TahunAkademik;
use App\Models\Siswa;

class CalculatePrometheusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promethee:calculate {--kategori=all : Category to calculate (khusus, umum, or all)} {--kuota=10 : Quota for khusus category}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate PROMETHEE ranking for student selection';

    protected $prometheusService;

    public function __construct(PrometheusService $prometheusService)
    {
        parent::__construct();
        $this->prometheusService = $prometheusService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $kategori = $this->option('kategori');
        $kuota = (int) $this->option('kuota');

        $tahunAkademik = TahunAkademik::getActive();
        if (!$tahunAkademik) {
            $this->error('Tidak ada tahun akademik yang aktif.');
            return 1;
        }

        $this->info("Menjalankan perhitungan PROMETHEE untuk tahun akademik: {$tahunAkademik->tahun} {$tahunAkademik->semester}");

        if ($kategori === 'all' || $kategori === 'khusus') {
            $this->calculateKhusus($kuota);
        }

        if ($kategori === 'all' || $kategori === 'umum') {
            $this->calculateUmum();
        }

        $this->info('Perhitungan PROMETHEE selesai!');
        return 0;
    }

    private function calculateKhusus($kuota)
    {
        $this->info('Menghitung ranking kategori Khusus...');

        $khususCount = Siswa::where('kategori', 'khusus')
            ->whereHas('tahunAkademik', function($q) {
                $q->where('is_active', true);
            })
            ->count();

        if ($khususCount === 0) {
            $this->warn('Tidak ada siswa kategori khusus.');
            return;
        }

        $result = $this->prometheusService->calculateRanking('khusus', null, $kuota);

        if (isset($result['error'])) {
            $this->error("Error kategori khusus: {$result['error']}");
        } else {
            $this->info("✓ Berhasil menghitung ranking {$khususCount} siswa kategori khusus dengan kuota {$kuota}");
        }
    }

    private function calculateUmum()
    {
        $this->info('Menghitung ranking kategori Umum...');

        $umumCount = Siswa::where('kategori', 'umum')
            ->whereHas('tahunAkademik', function($q) {
                $q->where('is_active', true);
            })
            ->count();

        if ($umumCount === 0) {
            $this->warn('Tidak ada siswa kategori umum.');
            return;
        }

        $result = $this->prometheusService->calculateRanking('umum');

        if (isset($result['error'])) {
            $this->error("Error kategori umum: {$result['error']}");
        } else {
            $this->info("✓ Berhasil menghitung ranking {$umumCount} siswa kategori umum");
        }
    }
}
