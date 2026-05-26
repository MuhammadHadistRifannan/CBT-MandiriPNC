<?php

namespace App\Providers;

use App\Services\CetakIdentitasService;
use Illuminate\Support\ServiceProvider;
use View;

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
        View::composer('layouts.dashboard.sidebar', function ($view) {
            $cetakAccess = null;

            if (auth()->check()) {
                $cetakAccess = app(CetakIdentitasService::class)->accessFor(auth()->user());
            }

            $view->with('cetakAccess', $cetakAccess);
        });
    }
}
