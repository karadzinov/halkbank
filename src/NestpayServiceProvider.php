<?php

namespace Nestpay\Laravel;

use Illuminate\Support\ServiceProvider;

class NestpayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nestpay.php', 'nestpay');

        $this->app->singleton(Contracts\NestpayContract::class, function ($app) {
            return new Support\Nestpay($app['config']->get('nestpay'));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/nestpay.php' => config_path('nestpay.php'),
        ], 'nestpay-config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nestpay');
    }
}


