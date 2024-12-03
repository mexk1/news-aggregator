<?php

namespace App\Providers;

use App\Console\Commands\PullNews;
use App\Services\News\Contracts\NewsRepository;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            NewsRepository::class, 
            fn () => new \App\Services\News\NewsRepository()
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schedule::command(PullNews::class)->dailyAt('00:00');
    }
}
