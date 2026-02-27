<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

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
    public function boot(UrlGenerator $url): void
    {
        // HTTPS uniquement en production (Render) pour éviter le mixed content,
        // mais laisser HTTP en local pour que le CSS fonctionne.
        if (app()->environment('production')) {
            $url->forceScheme('https');
        }
    }
}
