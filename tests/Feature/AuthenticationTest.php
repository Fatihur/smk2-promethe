<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Jurusan;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_admin_can_access_admin_routes()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/jurusan');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/kriteria');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/tahun-akademik');
        $response->assertStatus(200);
    }

    public function test_panitia_can_access_panitia_routes()
    {
        $panitia = User::factory()->create(['role' => 'panitia']);

        $response = $this->actingAs($panitia)->get('/panitia/siswa');
        $response->assertStatus(200);

        $response = $this->actingAs($panitia)->get('/panitia/promethee');
        $response->assertStatus(200);

        $response = $this->actingAs($panitia)->get('/panitia/reports');
        $response->assertStatus(200);
    }

    public function test_ketua_jurusan_can_access_validation_routes()
    {
        $jurusan = Jurusan::factory()->create();
        $ketuaJurusan = User::factory()->create([
            'role' => 'ketua_jurusan',
            'jurusan_id' => $jurusan->id
        ]);

        $response = $this->actingAs($ketuaJurusan)->get('/ketua-jurusan/validation');
        $response->assertStatus(200);
    }

    public function test_role_based_access_control()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $panitia = User::factory()->create(['role' => 'panitia']);
        $ketuaJurusan = User::factory()->create(['role' => 'ketua_jurusan']);

        // Admin cannot access panitia routes
        $response = $this->actingAs($admin)->get('/panitia/siswa');
        $response->assertStatus(403);

        // Panitia cannot access admin routes
        $response = $this->actingAs($panitia)->get('/admin/jurusan');
        $response->assertStatus(403);

        // Ketua Jurusan cannot access admin routes
        $response = $this->actingAs($ketuaJurusan)->get('/admin/jurusan');
        $response->assertStatus(403);

        // Ketua Jurusan cannot access panitia routes
        $response = $this->actingAs($ketuaJurusan)->get('/panitia/siswa');
        $response->assertStatus(403);
    }

    public function test_unauthenticated_users_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/jurusan');
        $response->assertRedirect('/login');

        $response = $this->get('/panitia/siswa');
        $response->assertRedirect('/login');

        $response = $this->get('/ketua-jurusan/validation');
        $response->assertRedirect('/login');
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_dashboard_redirects_based_on_role()
    {
        // Admin should see admin dashboard
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');

        // Panitia should see panitia dashboard
        $panitia = User::factory()->create(['role' => 'panitia']);
        $response = $this->actingAs($panitia)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('panitia.dashboard');

        // Ketua Jurusan should see ketua jurusan dashboard
        $jurusan = Jurusan::factory()->create();
        $ketuaJurusan = User::factory()->create([
            'role' => 'ketua_jurusan',
            'jurusan_id' => $jurusan->id
        ]);
        $response = $this->actingAs($ketuaJurusan)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('ketua-jurusan.dashboard');
    }

    public function test_password_validation_requirements()
    {
        $response = $this->post('/login', [
            'username' => '',
            'password' => ''
        ]);

        $response->assertSessionHasErrors(['username', 'password']);
    }

    public function test_username_must_be_unique_during_registration()
    {
        User::factory()->create(['username' => 'existinguser']);

        // This would be tested if we had a registration endpoint
        // For now, we test that usernames are unique in the database
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create(['username' => 'existinguser']);
    }
}
