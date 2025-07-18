<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Articles\Contracts\ArticleRepositoryInterface;
use App\Repositories\Articles\ArticleRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
