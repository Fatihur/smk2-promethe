<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Services\BreadcrumbService;

class AdminLTEServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share user role and breadcrumbs with all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('userRole', Auth::user()->role);
                $view->with('currentUser', Auth::user());
                $view->with('breadcrumbs', BreadcrumbService::generate());
            }
        });

        // Add custom CSS and JS for AdminLTE
        View::composer('adminlte::page', function ($view) {
            $view->with('customCss', [
                asset('css/custom-adminlte.css'),
            ]);
            $view->with('customJs', [
                asset('js/custom-adminlte.js'),
            ]);
        });
    }
}
