<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Role-based gates for menu access (AdminLTE uses 'can' attribute)
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('panitia', function ($user) {
            return $user->role === 'panitia';
        });

        Gate::define('ketua_jurusan', function ($user) {
            return $user->role === 'ketua_jurusan';
        });

        // Alternative gate names for AdminLTE compatibility
        Gate::define('access-admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('access-panitia', function ($user) {
            return $user->role === 'panitia';
        });

        Gate::define('access-ketua-jurusan', function ($user) {
            return $user->role === 'ketua_jurusan';
        });

        // Specific permission gates
        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-master-data', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-students', function ($user) {
            return in_array($user->role, ['admin', 'panitia']);
        });

        Gate::define('manage-selection-process', function ($user) {
            return in_array($user->role, ['admin', 'panitia']);
        });

        Gate::define('view-reports', function ($user) {
            return in_array($user->role, ['admin', 'panitia', 'ketua_jurusan']);
        });

        Gate::define('validate-results', function ($user) {
            return $user->role === 'ketua_jurusan';
        });

        // Department-specific gates
        Gate::define('access-department-data', function ($user, $jurusanId = null) {
            if ($user->role === 'admin') {
                return true;
            }
            
            if ($user->role === 'ketua_jurusan') {
                return $jurusanId ? $user->jurusan_id == $jurusanId : true;
            }
            
            return false;
        });
    }
}
