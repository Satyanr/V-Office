<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\KaryawanTbl;
use App\Models\ApprovalTbl;
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

        View::composer('*', function ($view) {
        $view->with(
            'pendingCount',
            ApprovalTbl::where('approval', 'Pending')->count()
        );
    });
    }
}
