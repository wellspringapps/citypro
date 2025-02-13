<?php

namespace App\Providers;
use Laravel\Folio\Folio;
use App\Http\Middleware\IsPro;
use Illuminate\Support\ServiceProvider;


class FolioServiceProvider extends ServiceProvider
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


        Folio::path(resource_path('views/pages'))->middleware([
            '/app/*' => ['auth']
        ]);
    }
}
