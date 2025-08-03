<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Services\MenuService;

class MenuServiceProvider extends ServiceProvider
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
        // Set the menu dynamically based on user role
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $menuItems = MenuService::getMenuItems();
                Config::set('adminlte.menu', $menuItems);
            }
        });
    }
}
