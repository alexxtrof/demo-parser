<?php

namespace App\Providers;

use App\Services\Parser\NewsParser;
use App\Services\Parser\RbkNewsParser;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(NewsParser::class, RbkNewsParser::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Сменил шаблон пагинации чтобы не возиться с версткой
        Paginator::useBootstrap();
    }
}
