<?php

namespace Zus1\LaravelAuth\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelAuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/laravel-auth.php' => config_path('laravel-auth.php'),
        ]);

        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');

        $this->publishesMigrations([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../../resources/views/mail/authentication', 'mail/authentication');
    }
}