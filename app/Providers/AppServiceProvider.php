<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Model\ArticleType::observe(\App\Observers\ArticleTypeObserver::class);
        \App\Model\Brand::observe(\App\Observers\BrandObserver::class);

        Carbon::setLocale('zh');
        Schema::defaultStringLength(191);
        CarbonInterval::setLocale('zh');
        Carbon::useMonthsOverflow(false);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
