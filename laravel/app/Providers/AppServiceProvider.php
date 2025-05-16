<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HtmlPurifierService;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HtmlPurifierService::class, function () {
            return new HtmlPurifierService();
        });

        $this->app->singleton(ImageManager::class, function () {
            return new ImageManager(new ImagickDriver());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
