<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class BreadcrumbService
{
    /**
     * Generate breadcrumbs based on current route
     */
    public static function generate(): array
    {
        $routeName = Route::currentRouteName();
        $user = Auth::user();
        
        if (!$routeName || !$user) {
            return [];
        }

        $breadcrumbs = [];
        
        // Always start with Dashboard
        $breadcrumbs[] = [
            'text' => 'Dashboard',
            'url' => route('dashboard'),
            'icon' => 'fas fa-tachometer-alt'
        ];

        // Generate breadcrumbs based on route patterns
        $routeParts = explode('.', $routeName);
        
        switch ($routeParts[0]) {
            case 'admin':
                $breadcrumbs = array_merge($breadcrumbs, self::getAdminBreadcrumbs($routeParts));
                break;
            case 'panitia':
                $breadcrumbs = array_merge($breadcrumbs, self::getPanitiaBreadcrumbs($routeParts));
                break;
            case 'ketua-jurusan':
                $breadcrumbs = array_merge($breadcrumbs, self::getKetuaJurusanBreadcrumbs($routeParts));
                break;
            case 'status':
                $breadcrumbs[] = [
                    'text' => 'Status Proses',
                    'url' => route('status.dashboard'),
                    'icon' => 'fas fa-chart-line'
                ];
                break;
        }

        return $breadcrumbs;
    }

    /**
     * Generate admin breadcrumbs
     */
    private static function getAdminBreadcrumbs(array $routeParts): array
    {
        $breadcrumbs = [];
        
        if (count($routeParts) < 2) {
            return $breadcrumbs;
        }

        switch ($routeParts[1]) {
            case 'jurusan':
                $breadcrumbs[] = [
                    'text' => 'Master Data',
                    'icon' => 'fas fa-database'
                ];
                $breadcrumbs[] = [
                    'text' => 'Kelola Jurusan',
                    'url' => route('admin.jurusan.index'),
                    'icon' => 'fas fa-graduation-cap'
                ];
                
                if (isset($routeParts[2])) {
                    switch ($routeParts[2]) {
                        case 'create':
                            $breadcrumbs[] = ['text' => 'Tambah Jurusan'];
                            break;
                        case 'edit':
                            $breadcrumbs[] = ['text' => 'Edit Jurusan'];
                            break;
                        case 'show':
                            $breadcrumbs[] = ['text' => 'Detail Jurusan'];
                            break;
                    }
                }
                break;

            case 'master-kriteria':
                $breadcrumbs[] = [
                    'text' => 'Master Kriteria',
                    'url' => route('admin.master-kriteria.index'),
                    'icon' => 'fas fa-list-ol'
                ];

                if (isset($routeParts[2])) {
                    switch ($routeParts[2]) {
                        case 'create':
                            $breadcrumbs[] = ['text' => 'Tambah Master Kriteria'];
                            break;
                        case 'edit':
                            $breadcrumbs[] = ['text' => 'Edit Master Kriteria'];
                            break;
                        case 'show':
                            $breadcrumbs[] = ['text' => 'Detail Master Kriteria'];
                            break;
                    }
                }
                break;

            case 'kriteria-overview':
                $breadcrumbs[] = [
                    'text' => 'Overview Kriteria',
                    'url' => route('admin.kriteria-overview.index'),
                    'icon' => 'fas fa-table'
                ];
                break;

            case 'kriteria-jurusan':
                $breadcrumbs[] = [
                    'text' => 'Kelola Jurusan',
                    'url' => route('admin.jurusan.index'),
                    'icon' => 'fas fa-graduation-cap'
                ];
                $breadcrumbs[] = [
                    'text' => 'Kriteria Jurusan',
                    'icon' => 'fas fa-clipboard-list'
                ];
                break;

            case 'tahun-akademik':
                $breadcrumbs[] = [
                    'text' => 'Master Data',
                    'icon' => 'fas fa-database'
                ];
                $breadcrumbs[] = [
                    'text' => 'Tahun Akademik',
                    'url' => route('admin.tahun-akademik.index'),
                    'icon' => 'fas fa-calendar-alt'
                ];
                break;

            case 'users':
                $breadcrumbs[] = [
                    'text' => 'Manajemen Pengguna',
                    'url' => route('admin.users.index'),
                    'icon' => 'fas fa-users'
                ];

                if (isset($routeParts[2])) {
                    switch ($routeParts[2]) {
                        case 'create':
                            $breadcrumbs[] = ['text' => 'Tambah User'];
                            break;
                        case 'edit':
                            $breadcrumbs[] = ['text' => 'Edit User'];
                            break;
                        case 'show':
                            $breadcrumbs[] = ['text' => 'Detail User'];
                            break;
                    }
                }
                break;

            case 'selection-process':
                $breadcrumbs[] = [
                    'text' => 'Proses Seleksi',
                    'icon' => 'fas fa-cogs'
                ];

                if (isset($routeParts[2])) {
                    switch ($routeParts[2]) {
                        case 'khusus':
                            $breadcrumbs[] = ['text' => 'Kategori Khusus'];
                            break;
                        case 'umum':
                            $breadcrumbs[] = ['text' => 'Kategori Umum'];
                            break;
                        case 'monitor':
                            $breadcrumbs[] = ['text' => 'Monitor Proses'];
                            break;
                    }
                }
                break;

            case 'results':
                $breadcrumbs[] = [
                    'text' => 'Hasil Seleksi',
                    'url' => route('admin.results.index'),
                    'icon' => 'fas fa-trophy'
                ];
                break;

            case 'reports':
                $breadcrumbs[] = [
                    'text' => 'Laporan',
                    'icon' => 'fas fa-chart-bar'
                ];

                if (isset($routeParts[2])) {
                    switch ($routeParts[2]) {
                        case 'statistics':
                            $breadcrumbs[] = ['text' => 'Statistik'];
                            break;
                        case 'export':
                            $breadcrumbs[] = ['text' => 'Export Data'];
                            break;
                    }
                }
                break;

            case 'profile':
                $breadcrumbs[] = [
                    'text' => 'Profil Saya',
                    'url' => route('profile.show'),
                    'icon' => 'fas fa-user'
                ];

                if (isset($routeParts[1])) {
                    switch ($routeParts[1]) {
                        case 'edit':
                            $breadcrumbs[] = ['text' => 'Edit Profil'];
                            break;
                    }
                }
                break;
        }

        return $breadcrumbs;
    }

    /**
     * Generate panitia breadcrumbs
     */
    private static function getPanitiaBreadcrumbs(array $routeParts): array
    {
        $breadcrumbs = [];
        
        if (count($routeParts) < 2) {
            return $breadcrumbs;
        }

        switch ($routeParts[1]) {
            case 'siswa':
                $breadcrumbs[] = [
                    'text' => 'Data Siswa',
                    'url' => route('panitia.siswa.index'),
                    'icon' => 'fas fa-user-graduate'
                ];
                break;

            case 'promethee':
                $breadcrumbs[] = [
                    'text' => 'Proses PROMETHEE',
                    'icon' => 'fas fa-calculator'
                ];
                
                if (isset($routeParts[2])) {
                    switch ($routeParts[2]) {
                        case 'khusus':
                            $breadcrumbs[] = [
                                'text' => 'Kategori Khusus',
                                'url' => route('panitia.promethee.khusus.form'),
                                'icon' => 'fas fa-star'
                            ];
                            break;
                        case 'umum':
                            $breadcrumbs[] = [
                                'text' => 'Kategori Umum',
                                'url' => route('panitia.promethee.umum.form'),
                                'icon' => 'fas fa-users'
                            ];
                            break;
                    }
                }
                break;

            case 'reports':
                $breadcrumbs[] = [
                    'text' => 'Laporan & Cetak',
                    'icon' => 'fas fa-file-alt'
                ];
                
                if (isset($routeParts[2])) {
                    switch ($routeParts[2]) {
                        case 'hasil-seleksi':
                            $breadcrumbs[] = [
                                'text' => 'Hasil Seleksi',
                                'url' => route('panitia.reports.hasil-seleksi'),
                                'icon' => 'fas fa-trophy'
                            ];
                            break;
                        case 'ranking':
                            $breadcrumbs[] = [
                                'text' => 'Ranking Siswa',
                                'url' => route('panitia.reports.ranking'),
                                'icon' => 'fas fa-sort-numeric-down'
                            ];
                            break;
                        case 'statistik':
                            $breadcrumbs[] = [
                                'text' => 'Statistik',
                                'url' => route('panitia.reports.statistik'),
                                'icon' => 'fas fa-chart-pie'
                            ];
                            break;
                        case 'daftar-lulus':
                            $breadcrumbs[] = [
                                'text' => 'Daftar Lulus',
                                'url' => route('panitia.reports.daftar-lulus'),
                                'icon' => 'fas fa-list-alt'
                            ];
                            break;
                    }
                }
                break;
        }

        return $breadcrumbs;
    }

    /**
     * Generate ketua jurusan breadcrumbs
     */
    private static function getKetuaJurusanBreadcrumbs(array $routeParts): array
    {
        $breadcrumbs = [];
        
        if (count($routeParts) < 2) {
            return $breadcrumbs;
        }

        switch ($routeParts[1]) {
            case 'validation':
                $breadcrumbs[] = [
                    'text' => 'Validasi Hasil',
                    'icon' => 'fas fa-check-circle'
                ];
                
                if (isset($routeParts[2])) {
                    switch ($routeParts[2]) {
                        case 'bulk':
                            $breadcrumbs[] = [
                                'text' => 'Validasi Massal',
                                'url' => route('ketua-jurusan.validation.bulk.form'),
                                'icon' => 'fas fa-check-double'
                            ];
                            break;
                    }
                }
                break;

            case 'reports':
                $breadcrumbs[] = [
                    'text' => 'Laporan Jurusan',
                    'url' => route('ketua-jurusan.reports.index'),
                    'icon' => 'fas fa-chart-line'
                ];
                break;
        }

        return $breadcrumbs;
    }
}
