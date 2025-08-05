<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class MenuService
{
    /**
     * Get menu items based on user role
     */
    public static function getMenuItems(): array
    {
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        $menu = [];

        // Common items for all authenticated users
        $menu = array_merge($menu, self::getCommonMenuItems());

        // Role-specific menu items
        switch ($user->role) {
            case 'admin':
                $menu = array_merge($menu, self::getAdminMenuItems());
                break;
            case 'panitia':
                $menu = array_merge($menu, self::getPanitiaMenuItems());
                break;
            case 'ketua_jurusan':
                $menu = array_merge($menu, self::getKetuaJurusanMenuItems());
                break;
        }

        // Account settings (common for all)
        $menu = array_merge($menu, self::getAccountMenuItems());

        return $menu;
    }

    /**
     * Common menu items for all authenticated users
     */
    private static function getCommonMenuItems(): array
    {
        return [
            // Navbar items
            [
                'type' => 'navbar-search',
                'text' => 'search',
                'topnav_right' => true,
            ],
            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],

            // Sidebar items
            [
                'type' => 'sidebar-menu-search',
                'text' => 'search',
            ],

            // Dashboard
            [
                'text' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
                'active' => ['dashboard', 'admin.dashboard', 'panitia.dashboard', 'ketua-jurusan.dashboard'],
            ],

            // Status Dashboard (All Users)
            [
                'text' => 'Status Proses',
                'route' => 'status.dashboard',
                'icon' => 'fas fa-fw fa-chart-line',
                'active' => ['status.*'],
            ],
        ];
    }

    /**
     * Admin menu items
     */
    private static function getAdminMenuItems(): array
    {
        return [
            ['header' => 'MANAJEMEN DATA'],
            
            // Master Data submenu
            [
                'text' => 'Master Data',
                'icon' => 'fas fa-fw fa-database',
                'submenu' => [
                    [
                        'text' => 'Kelola Jurusan',
                        'route' => 'admin.jurusan.index',
                        'icon' => 'fas fa-fw fa-graduation-cap',
                        'active' => ['admin.jurusan.*'],
                    ],
                    [
                        'text' => 'Master Kriteria',
                        'route' => 'admin.master-kriteria.index',
                        'icon' => 'fas fa-fw fa-list-ol',
                        'active' => ['admin.master-kriteria.*'],
                    ],
                    [
                        'text' => 'Tahun Akademik',
                        'route' => 'admin.tahun-akademik.index',
                        'icon' => 'fas fa-fw fa-calendar-alt',
                        'active' => ['admin.tahun-akademik.*'],
                    ],
                ],
            ],

            // User Management
            [
                'text' => 'Manajemen Pengguna',
                'route' => 'admin.users.index',
                'icon' => 'fas fa-fw fa-users',
                'active' => ['admin.users.*'],
            ],

            ['header' => 'PROSES SELEKSI'],

            // Selection Process
            [
                'text' => 'Proses Seleksi',
                'icon' => 'fas fa-fw fa-cogs',
                'submenu' => [
                    [
                        'text' => 'Kategori Khusus',
                        'route' => 'admin.selection-process.khusus',
                        'icon' => 'fas fa-fw fa-star',
                        'active' => ['admin.selection-process.khusus*'],
                    ],
                    [
                        'text' => 'Kategori Umum',
                        'route' => 'admin.selection-process.umum',
                        'icon' => 'fas fa-fw fa-users',
                        'active' => ['admin.selection-process.umum*'],
                    ],

                ],
            ],

            // Results and Reports
            [
                'text' => 'Hasil & Laporan',
                'icon' => 'fas fa-fw fa-chart-bar',
                'submenu' => [
                    [
                        'text' => 'Hasil Seleksi',
                        'route' => 'admin.results.index',
                        'icon' => 'fas fa-fw fa-trophy',
                        'active' => ['admin.results.*'],
                    ],

                    [
                        'text' => 'Export Data',
                        'route' => 'admin.reports.export',
                        'icon' => 'fas fa-fw fa-download',
                        'active' => ['admin.reports.export*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Panitia menu items
     */
    private static function getPanitiaMenuItems(): array
    {
        return [
            ['header' => 'PANITIA PPDB'],
            
            // Student Management
            [
                'text' => 'Data Siswa',
                'route' => 'panitia.siswa.index',
                'icon' => 'fas fa-fw fa-user-graduate',
                'active' => ['panitia.siswa.*'],
            ],

            // Nilai Siswa Management
            [
                'text' => 'Input Nilai Siswa',
                'icon' => 'fas fa-fw fa-edit',
                'submenu' => [
                    [
                        'text' => 'Per Jurusan',
                        'route' => 'panitia.nilai-jurusan.index',
                        'icon' => 'fas fa-fw fa-graduation-cap',
                        'active' => ['panitia.nilai-jurusan.*'],
                    ],
                    [
                        'text' => 'Individual',
                        'route' => 'panitia.siswa.index',
                        'icon' => 'fas fa-fw fa-user',
                        'active' => ['panitia.siswa.nilai.*'],
                    ],
                ],
            ],

            // PROMETHEE Process
            [
                'text' => 'Proses PROMETHEE',
                'icon' => 'fas fa-fw fa-calculator',
                'submenu' => [
                    [
                        'text' => 'Dashboard PROMETHEE',
                        'route' => 'panitia.promethee.index',
                        'icon' => 'fas fa-fw fa-tachometer-alt',
                        'active' => ['panitia.promethee.index'],
                    ],
                    [
                        'text' => 'Kategori Khusus',
                        'route' => 'panitia.promethee.khusus.form',
                        'icon' => 'fas fa-fw fa-star',
                        'active' => ['panitia.promethee.khusus.*'],
                    ],
                    [
                        'text' => 'Kategori Umum',
                        'route' => 'panitia.promethee.umum.form',
                        'icon' => 'fas fa-fw fa-users',
                        'active' => ['panitia.promethee.umum.*'],
                    ],
                ],
            ],

            // Reports
            [
                'text' => 'Laporan & Cetak',
                'icon' => 'fas fa-fw fa-file-alt',
                'submenu' => [
                    [
                        'text' => 'Dashboard Laporan',
                        'route' => 'panitia.reports.index',
                        'icon' => 'fas fa-fw fa-tachometer-alt',
                        'active' => ['panitia.reports.index'],
                    ],
                    [
                        'text' => 'Hasil Seleksi',
                        'route' => 'panitia.reports.hasil-seleksi',
                        'icon' => 'fas fa-fw fa-trophy',
                        'active' => ['panitia.reports.hasil-seleksi*'],
                    ],
                    [
                        'text' => 'Ranking Siswa',
                        'route' => 'panitia.reports.ranking',
                        'icon' => 'fas fa-fw fa-sort-numeric-down',
                        'active' => ['panitia.reports.ranking*'],
                    ],
                    [
                        'text' => 'Statistik',
                        'route' => 'panitia.reports.statistik',
                        'icon' => 'fas fa-fw fa-chart-pie',
                        'active' => ['panitia.reports.statistik*'],
                    ],
                    [
                        'text' => 'Daftar Lulus',
                        'route' => 'panitia.reports.daftar-lulus',
                        'icon' => 'fas fa-fw fa-list-alt',
                        'active' => ['panitia.reports.daftar-lulus*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Ketua Jurusan menu items
     */
    private static function getKetuaJurusanMenuItems(): array
    {
        return [
            ['header' => 'KETUA JURUSAN'],
            
            // Validation
            [
                'text' => 'Validasi Hasil',
                'icon' => 'fas fa-fw fa-check-circle',
                'submenu' => [
                    [
                        'text' => 'Daftar Validasi',
                        'route' => 'ketua-jurusan.validation.index',
                        'icon' => 'fas fa-fw fa-list',
                        'active' => ['ketua-jurusan.validation.index'],
                    ],
                    [
                        'text' => 'Validasi Massal',
                        'route' => 'ketua-jurusan.validation.bulk.form',
                        'icon' => 'fas fa-fw fa-check-double',
                        'active' => ['ketua-jurusan.validation.bulk.*'],
                    ],
                ],
            ],

            // Department Reports
            [
                'text' => 'Laporan Jurusan',
                'route' => 'ketua-jurusan.reports.index',
                'icon' => 'fas fa-fw fa-chart-line',
                'active' => ['ketua-jurusan.reports.*'],
            ],
        ];
    }

    /**
     * Account menu items (common for all users)
     */
    private static function getAccountMenuItems(): array
    {
        return [
            ['header' => 'PENGATURAN AKUN'],
            [
                'text' => 'Profil Saya',
                'route' => 'profile.show',
                'icon' => 'fas fa-fw fa-user',
                'active' => ['profile.*'],
            ],
            [
                'text' => 'Logout',
                'route' => 'logout',
                'icon' => 'fas fa-fw fa-sign-out-alt',
                'method' => 'post',
            ],
        ];
    }

    /**
     * Check if current route matches any of the active patterns
     */
    public static function isActiveRoute(array $patterns): bool
    {
        $currentRoute = Route::currentRouteName();
        
        foreach ($patterns as $pattern) {
            if (fnmatch($pattern, $currentRoute)) {
                return true;
            }
        }
        
        return false;
    }
}
