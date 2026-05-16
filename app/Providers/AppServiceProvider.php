<?php

namespace App\Providers;

use App\Models\Dokumen;
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
        //

        View::composer('*', function ($view) {

            $dokumenLengkap = false;

            if (auth()->check()) {

                $dokumen = Dokumen::where(
                    'user_id',
                    auth()->id()
                )->first();

                if ($dokumen){
                    $dokumenLengkap = $dokumen &&
                        $dokumen->ijazah ||
                        $dokumen->suket;
                }

            }

            $view->with(
                'dokumenLengkap',
                $dokumenLengkap
            );
        });
    }
}
