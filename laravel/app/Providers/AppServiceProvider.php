<?php

namespace App\Providers;

use App\Helper\HtmlPurifierHelper;
use App\Interfaces\CommentAttachmentRepositoryInterface;
use App\Interfaces\CommentRepositoryInterface;
use App\Repositories\CachedCommentRepository;
use App\Repositories\CommentAttachmentRepository;
use App\Repositories\CommentRepository;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HtmlPurifierHelper::class, function () {
            return new HtmlPurifierHelper();
        });

        $this->app->singleton(ImageManager::class, function () {
            return new ImageManager(new ImagickDriver());
        });

        $this->app->singleton(CommentRepositoryInterface::class, function () {
            $repository = new CommentRepository();

            return new CachedCommentRepository($repository);
        });

        $this->app->singleton(CommentAttachmentRepositoryInterface::class, function () {
            return new CommentAttachmentRepository();
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
