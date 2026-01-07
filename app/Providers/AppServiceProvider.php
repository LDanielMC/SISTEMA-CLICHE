<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

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
        // Tu configuración existente para la base de datos
        Schema::defaultStringLength(191);

        // AGREGA ESTO: Forzar HTTPS si la URL es de Ngrok
        if (str_contains(config('app.url'), 'ngrok')) {
            URL::forceScheme('https');
        }
    }
}