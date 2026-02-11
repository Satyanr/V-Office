<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\KaryawanTbl;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['pages.absensi', 'pages.pengajuan'], function ($view) {
            $view->with('karyawans', KaryawanTbl::orderBy('name')->get());
        });
    }
}
