<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\TahunAkademik;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $tahunAkademik;
    protected $jurusanTAB;
    protected $jurusanTSM;
    protected $jurusanTKJ;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->tahunAkademik = TahunAkademik::factory()->create(['is_active' => true]);
        $this->jurusanTAB = Jurusan::factory()->create(['kode_jurusan' => 'TAB']);
        $this->jurusanTSM = Jurusan::factory()->create(['kode_jurusan' => 'TSM']);
        $this->jurusanTKJ = Jurusan::factory()->create(['kode_jurusan' => 'TKJ']);
    }

    public function test_siswa_can_be_created()
    {
        $siswa = Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'pilihan_jurusan_1' => $this->jurusanTKJ->id,
        ]);

        $this->assertDatabaseHas('siswa', [
            'id' => $siswa->id,
            'tahun_akademik_id' => $this->tahunAkademik->id,
        ]);
    }

    public function test_siswa_automatically_categorized_as_khusus_for_tab_tsm()
    {
        // Test TAB
        $siswaTab = Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'pilihan_jurusan_1' => $this->jurusanTAB->id,
        ]);

        $this->assertEquals('khusus', $siswaTab->kategori);

        // Test TSM
        $siswaTsm = Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'pilihan_jurusan_1' => $this->jurusanTSM->id,
        ]);

        $this->assertEquals('khusus', $siswaTsm->kategori);
    }

    public function test_siswa_automatically_categorized_as_umum_for_other_jurusan()
    {
        $siswa = Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'pilihan_jurusan_1' => $this->jurusanTKJ->id,
        ]);

        $this->assertEquals('umum', $siswa->kategori);
    }

    public function test_siswa_no_pendaftaran_is_auto_generated()
    {
        $siswa = Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
        ]);

        $this->assertNotNull($siswa->no_pendaftaran);
        $this->assertStringStartsWith(date('Y'), $siswa->no_pendaftaran);
    }

    public function test_siswa_relationships()
    {
        $siswa = Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'pilihan_jurusan_1' => $this->jurusanTKJ->id,
            'pilihan_jurusan_2' => $this->jurusanTAB->id,
        ]);

        $this->assertInstanceOf(TahunAkademik::class, $siswa->tahunAkademik);
        $this->assertInstanceOf(Jurusan::class, $siswa->pilihanJurusan1);
        $this->assertInstanceOf(Jurusan::class, $siswa->pilihanJurusan2);
    }

    public function test_siswa_status_seleksi_default_is_pending()
    {
        $siswa = Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
        ]);

        $this->assertEquals('pending', $siswa->status_seleksi);
    }

    public function test_siswa_can_have_nilai()
    {
        $siswa = Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
        ]);

        $siswa->update([
            'nilai_tpa' => 85.5,
            'nilai_psikotes' => 90.0,
            'nilai_minat_bakat' => 1,
        ]);

        $this->assertEquals(85.5, $siswa->nilai_tpa);
        $this->assertEquals(90.0, $siswa->nilai_psikotes);
        $this->assertEquals(1, $siswa->nilai_minat_bakat);
    }
}
