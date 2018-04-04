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
