<?php

namespace App\Providers;

use App\Repositories\ShoeRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\PromoCodeRepository;
use App\Repositories\Contracts\ShoeRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\PromoCodeRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //daftar kan semua repository ke sini
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

        $this->app->bind(PromoCodeRepositoryInterface::class, PromoCodeRepository::class);

        $this->app->bind(ShoeRepositoryInterface::class, ShoeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
