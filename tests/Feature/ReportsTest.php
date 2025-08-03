<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\TahunAkademik;
use App\Models\PrometheusResult;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    protected $panitia;
    protected $tahunAkademik;
    protected $jurusan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->panitia = User::factory()->create(['role' => 'panitia']);
        $this->tahunAkademik = TahunAkademik::factory()->create(['is_active' => true]);
        $this->jurusan = Jurusan::factory()->create();
    }

    public function test_reports_dashboard_is_accessible()
    {
        $response = $this->actingAs($this->panitia)
            ->get(route('panitia.reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('panitia.reports.index');
    }

    public function test_hasil_seleksi_report_displays_correctly()
    {
        // Create test data
        Siswa::factory()->count(5)->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'pilihan_jurusan_1' => $this->jurusan->id,
        ]);

        $response = $this->actingAs($this->panitia)
            ->get(route('panitia.reports.hasil-seleksi'));

        $response->assertStatus(200);
        $response->assertViewIs('panitia.reports.hasil-seleksi');
        $response->assertViewHas('siswa');
    }

    public function test_hasil_seleksi_report_can_be_filtered()
    {
        // Create siswa with different categories
        Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'kategori' => 'khusus'
        ]);

        Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'kategori' => 'umum'
        ]);

        // Test filter by kategori
        $response = $this->actingAs($this->panitia)
            ->get(route('panitia.reports.hasil-seleksi', ['kategori' => 'khusus']));

        $response->assertStatus(200);
        $response->assertViewHas('kategori', 'khusus');
    }

    public function test_ranking_report_displays_correctly()
    {
        // Create PROMETHEE results
        $siswa = Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'kategori' => 'khusus'
        ]);

        PrometheusResult::factory()->create([
            'siswa_id' => $siswa->id,
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'kategori' => 'khusus',
            'ranking' => 1
        ]);

        $response = $this->actingAs($this->panitia)
            ->get(route('panitia.reports.ranking', ['kategori' => 'khusus']));

        $response->assertStatus(200);
        $response->assertViewIs('panitia.reports.ranking');
        $response->assertViewHas('results');
    }

    public function test_daftar_lulus_report_shows_only_accepted_students()
    {
        // Create siswa with different status
        $siswaLulus = Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'status_seleksi' => 'lulus',
            'jurusan_diterima_id' => $this->jurusan->id
        ]);

        Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'status_seleksi' => 'tidak_lulus'
        ]);

        $response = $this->actingAs($this->panitia)
            ->get(route('panitia.reports.daftar-lulus'));

        $response->assertStatus(200);
        $response->assertViewIs('panitia.reports.daftar-lulus');

        // Should only show lulus students
        $siswaLulusData = $response->viewData('siswaLulus');
        $this->assertCount(1, $siswaLulusData);
        $this->assertEquals($siswaLulus->id, $siswaLulusData->first()->id);
    }

    public function test_statistik_report_displays_correctly()
    {
        $response = $this->actingAs($this->panitia)
            ->get(route('panitia.reports.statistik'));

        $response->assertStatus(200);
        $response->assertViewIs('panitia.reports.statistik');
        $response->assertViewHas('stats');
    }

    public function test_print_hasil_seleksi_returns_printable_view()
    {
        Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
        ]);

        $response = $this->actingAs($this->panitia)
            ->get(route('panitia.reports.print.hasil-seleksi'));

        $response->assertStatus(200);
        $response->assertViewIs('panitia.reports.print.hasil-seleksi');
    }

    public function test_print_daftar_lulus_returns_printable_view()
    {
        Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
            'status_seleksi' => 'lulus',
            'jurusan_diterima_id' => $this->jurusan->id
        ]);

        $response = $this->actingAs($this->panitia)
            ->get(route('panitia.reports.print.daftar-lulus'));

        $response->assertStatus(200);
        $response->assertViewIs('panitia.reports.print.daftar-lulus');
    }

    public function test_excel_export_downloads_file()
    {
        Siswa::factory()->create([
            'tahun_akademik_id' => $this->tahunAkademik->id,
        ]);

        $response = $this->actingAs($this->panitia)
            ->get(route('panitia.reports.export.hasil-seleksi'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_statistik_excel_export_downloads_file()
    {
        $response = $this->actingAs($this->panitia)
            ->get(route('panitia.reports.export.statistik'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_unauthorized_users_cannot_access_reports()
    {
        $ketuaJurusan = User::factory()->create(['role' => 'ketua_jurusan']);

        $response = $this->actingAs($ketuaJurusan)
            ->get(route('panitia.reports.index'));

        $response->assertStatus(403);
    }

    public function test_status_dashboard_is_accessible_by_all_authenticated_users()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $panitia = User::factory()->create(['role' => 'panitia']);
        $ketuaJurusan = User::factory()->create(['role' => 'ketua_jurusan']);

        // All roles should be able to access status dashboard
        $response = $this->actingAs($admin)->get(route('status.dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($panitia)->get(route('status.dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($ketuaJurusan)->get(route('status.dashboard'));
        $response->assertStatus(200);
    }

    public function test_api_status_returns_json_data()
    {
        $response = $this->actingAs($this->panitia)
            ->get(route('api.status'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'tahun_akademik',
            'siswa_stats',
            'promethee_stats',
            'validation_stats',
            'jurusan_stats',
            'process_status'
        ]);
    }
}
