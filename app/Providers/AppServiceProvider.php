<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Filament\Resources\Resource;
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
        FilamentAsset::register([
            Css::make('custom-stylesheet', __DIR__ . '/../../resources/css/custom.css'),
            Css::make('glightbox-stylesheet', __DIR__ . '/../../resources/js/glightbox/glightbox.min.css'),
        ]);

        FilamentAsset::register([
            Js::make('glightbox-js', __DIR__ . '/../../resources/js/glightbox/glightbox.min.js'),
            Js::make('custom-js', __DIR__ . '/../../resources/js/custom.js'),
        ]);
    }
}
