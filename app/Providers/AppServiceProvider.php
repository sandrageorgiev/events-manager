<?php

namespace App\Providers;

use App\Repositories\CouponRepositoryInterface;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\impl\CouponRepository;
use App\Repositories\impl\EventRepository;
use App\Repositories\impl\ImageRepository;
use App\Repositories\impl\TagRepository;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->singleton(TagRepositoryInterface::class, TagRepository::class);
        $this->app->singleton(EventRepositoryInterface::class, EventRepository::class);
        $this->app->singleton(ImageRepositoryInterface::class, ImageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
