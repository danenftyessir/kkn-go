<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * register any application services
     */
    public function register(): void
    {
        //
    }

    /**
     * bootstrap any application services
     */
    public function boot(): void
    {
        // force HTTPS di production
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        // prevent lazy loading di development untuk menghindari N+1 queries
        Model::preventLazyLoading(!$this->app->isProduction());

        // prevent silently discarding attributes
        Model::preventSilentlyDiscardingAttributes(!$this->app->isProduction());

        // prevent accessing missing attributes
        Model::preventAccessingMissingAttributes(!$this->app->isProduction());

        // customize pagination view
        // Paginator::useBootstrapFive(); // jika mau pakai bootstrap
        // Paginator::useTailwind(); // otomatis pakai tailwind di Laravel 11+
    }
}
