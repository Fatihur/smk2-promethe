<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LoginController::class, 'logout']);

// Password Reset Routes
Route::get('/password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');



// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Main Dashboard Route - redirects based on user role
    Route::get('/dashboard', function () {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'panitia':
                return redirect()->route('panitia.dashboard');
            case 'ketua_jurusan':
                return redirect()->route('ketua-jurusan.dashboard');
            default:
                abort(403, 'Unauthorized role');
        }
    })->name('dashboard');

    // Status Dashboard (accessible by all authenticated users)
    Route::get('/status', [\App\Http\Controllers\StatusController::class, 'dashboard'])->name('status.dashboard');
    Route::get('/api/status', [\App\Http\Controllers\StatusController::class, 'getStatusData'])->name('api.status');

    // Profile Management (accessible by all authenticated users)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::put('/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
    });



    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Jurusan Management
        Route::resource('jurusan', \App\Http\Controllers\Admin\JurusanController::class);

        // Master Kriteria Management
        Route::resource('master-kriteria', \App\Http\Controllers\Admin\MasterKriteriaController::class);
        Route::patch('master-kriteria/{master_kriterium}/toggle-status', [\App\Http\Controllers\Admin\MasterKriteriaController::class, 'toggleStatus'])
            ->name('master-kriteria.toggle-status');
        Route::delete('master-kriteria/{master_kriterium}/force-delete', [\App\Http\Controllers\Admin\MasterKriteriaController::class, 'forceDestroy'])
            ->name('master-kriteria.force-destroy');





        // Tahun Akademik Management
        Route::resource('tahun-akademik', \App\Http\Controllers\Admin\TahunAkademikController::class);
        Route::patch('tahun-akademik/{tahun_akademik}/set-active', [\App\Http\Controllers\Admin\TahunAkademikController::class, 'setActive'])
            ->name('tahun-akademik.set-active');
        Route::post('tahun-akademik/bulk-action', [\App\Http\Controllers\Admin\TahunAkademikController::class, 'bulkAction'])
            ->name('tahun-akademik.bulk-action');

        // User Management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // Selection Process Management (Admin Oversight)
        Route::prefix('selection-process')->name('selection-process.')->group(function () {
            Route::get('khusus', [\App\Http\Controllers\Admin\SelectionProcessController::class, 'khusus'])->name('khusus');
            Route::get('umum', [\App\Http\Controllers\Admin\SelectionProcessController::class, 'umum'])->name('umum');

        });

        // Results and Reports Management (Admin)
        Route::prefix('results')->name('results.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ResultController::class, 'index'])->name('index');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('export');

            // Export routes
            Route::get('export/siswa', [\App\Http\Controllers\Admin\ReportController::class, 'exportSiswa'])->name('export.siswa');
            Route::get('export/hasil-seleksi', [\App\Http\Controllers\Admin\ReportController::class, 'exportHasilSeleksi'])->name('export.hasil-seleksi');
            Route::get('export/ranking', [\App\Http\Controllers\Admin\ReportController::class, 'exportRanking'])->name('export.ranking');
            Route::get('export/statistik', [\App\Http\Controllers\Admin\ReportController::class, 'exportStatistik'])->name('export.statistik');
        });
    });

    // Panitia Routes
    Route::middleware(['role:panitia'])->prefix('panitia')->name('panitia.')->group(function () {
        Route::get('/dashboard', function () {
            return view('panitia.dashboard');
        })->name('dashboard');

        // Siswa Management
        Route::resource('siswa', \App\Http\Controllers\Panitia\SiswaController::class);
        Route::get('siswa-import', [\App\Http\Controllers\Panitia\SiswaController::class, 'showImport'])->name('siswa.import');
        Route::post('siswa-import', [\App\Http\Controllers\Panitia\SiswaController::class, 'import'])->name('siswa.import.store');
        Route::get('siswa-template', [\App\Http\Controllers\Panitia\SiswaController::class, 'downloadTemplate'])->name('siswa.template');
        Route::get('siswa-export', [\App\Http\Controllers\Panitia\SiswaController::class, 'export'])->name('siswa.export');

        // Individual Student Score Management
        Route::prefix('siswa')->name('siswa.')->group(function () {
            Route::prefix('{siswa}/nilai')->name('nilai.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Panitia\NilaiController::class, 'index'])->name('index');
                Route::get('create', [\App\Http\Controllers\Panitia\NilaiController::class, 'create'])->name('create');
                Route::post('store', [\App\Http\Controllers\Panitia\NilaiController::class, 'store'])->name('store');
                Route::get('edit', [\App\Http\Controllers\Panitia\NilaiController::class, 'edit'])->name('edit');
                Route::put('update', [\App\Http\Controllers\Panitia\NilaiController::class, 'update'])->name('update');
                Route::delete('{nilai}', [\App\Http\Controllers\Panitia\NilaiController::class, 'destroy'])->name('destroy');
            });
        });



        // Nilai Siswa Management (Per Jurusan)
        Route::prefix('nilai-jurusan')->name('nilai-jurusan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Panitia\NilaiJurusanController::class, 'index'])->name('index');
            Route::get('setup-data', [\App\Http\Controllers\Panitia\NilaiJurusanController::class, 'setupData'])->name('setup-data');
            Route::get('clear-nilai', [\App\Http\Controllers\Panitia\NilaiJurusanController::class, 'clearNilai'])->name('clear-nilai');
            Route::get('{jurusan}/siswa', [\App\Http\Controllers\Panitia\NilaiJurusanController::class, 'siswa'])->name('siswa');
            Route::get('{jurusan}/siswa/{siswa}/input', [\App\Http\Controllers\Panitia\NilaiJurusanController::class, 'inputNilai'])->name('input');
            Route::post('{jurusan}/siswa/{siswa}/store', [\App\Http\Controllers\Panitia\NilaiJurusanController::class, 'storeNilai'])->name('store');
            Route::get('{jurusan}/bulk', [\App\Http\Controllers\Panitia\NilaiJurusanController::class, 'bulkInput'])->name('bulk');
            Route::post('{jurusan}/bulk/store', [\App\Http\Controllers\Panitia\NilaiJurusanController::class, 'storeBulkNilai'])->name('bulk.store');
        });



        // PROMETHEE Management
        Route::prefix('promethee')->name('promethee.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Panitia\PrometheusController::class, 'index'])->name('index');

            // Khusus Category
            Route::get('khusus/form', [\App\Http\Controllers\Panitia\PrometheusController::class, 'khususForm'])->name('khusus.form');
            Route::post('khusus/run', [\App\Http\Controllers\Panitia\PrometheusController::class, 'runKhusus'])->name('khusus.run');
            Route::get('khusus/results', [\App\Http\Controllers\Panitia\PrometheusController::class, 'khususResults'])->name('khusus.results');
            Route::post('transfer-to-umum', [\App\Http\Controllers\Panitia\PrometheusController::class, 'transferToUmum'])->name('transfer.umum');

            // Umum Category
            Route::get('umum/form', [\App\Http\Controllers\Panitia\PrometheusController::class, 'umumForm'])->name('umum.form');
            Route::post('umum/run', [\App\Http\Controllers\Panitia\PrometheusController::class, 'runUmum'])->name('umum.run');
            Route::get('umum/results', [\App\Http\Controllers\Panitia\PrometheusController::class, 'umumResults'])->name('umum.results');
        });

        // Reports Management
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Panitia\ReportController::class, 'index'])->name('index');
            Route::get('/hasil-seleksi', [\App\Http\Controllers\Panitia\ReportController::class, 'hasilSeleksi'])->name('hasil-seleksi');
            Route::get('/ranking', [\App\Http\Controllers\Panitia\ReportController::class, 'ranking'])->name('ranking');
            Route::get('/statistik', [\App\Http\Controllers\Panitia\ReportController::class, 'statistik'])->name('statistik');
            Route::get('/daftar-lulus', [\App\Http\Controllers\Panitia\ReportController::class, 'daftarLulus'])->name('daftar-lulus');

            // Print routes
            Route::get('/print/hasil-seleksi', [\App\Http\Controllers\Panitia\ReportController::class, 'printHasilSeleksi'])->name('print.hasil-seleksi');
            Route::get('/print/ranking', [\App\Http\Controllers\Panitia\ReportController::class, 'printRanking'])->name('print.ranking');
            Route::get('/print/daftar-lulus', [\App\Http\Controllers\Panitia\ReportController::class, 'printDaftarLulus'])->name('print.daftar-lulus');

            // Export routes
            Route::get('/export/siswa', [\App\Http\Controllers\Panitia\SiswaController::class, 'export'])->name('export.siswa');
            Route::get('/export/hasil-seleksi', [\App\Http\Controllers\Panitia\ReportController::class, 'exportHasilSeleksi'])->name('export.hasil-seleksi');
            Route::get('/export/ranking', [\App\Http\Controllers\Panitia\ReportController::class, 'exportRanking'])->name('export.ranking');
            Route::get('/export/statistik', [\App\Http\Controllers\Panitia\ReportController::class, 'exportStatistik'])->name('export.statistik');
        });
    });

    // Ketua Jurusan Routes
    Route::middleware(['role:ketua_jurusan'])->prefix('ketua-jurusan')->name('ketua-jurusan.')->group(function () {
        Route::get('/dashboard', function () {
            return view('ketua-jurusan.dashboard');
        })->name('dashboard');

        // Validation Routes
        Route::prefix('validation')->name('validation.')->group(function () {
            Route::get('/', [\App\Http\Controllers\KetuaJurusan\ValidationController::class, 'index'])->name('index');
            Route::get('/bulk', [\App\Http\Controllers\KetuaJurusan\ValidationController::class, 'bulkForm'])->name('bulk.form');
            Route::post('/bulk', [\App\Http\Controllers\KetuaJurusan\ValidationController::class, 'bulkValidate'])->name('bulk.validate');
            Route::get('/{result}', [\App\Http\Controllers\KetuaJurusan\ValidationController::class, 'show'])->name('show');
            Route::post('/{result}', [\App\Http\Controllers\KetuaJurusan\ValidationController::class, 'validate'])->name('validate');
        });

        // Reports Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', function () {
                return view('ketua-jurusan.reports.index');
            })->name('index');
        });
    });

});
