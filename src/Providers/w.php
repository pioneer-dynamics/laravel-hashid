<?php

namespace PioneerDynamics\LaravelHashid\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use PioneerDynamics\LaravelHashid\Console\GenerateHashIdKey;

class HashIdServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('command.hashid:key', GenerateHashIdKey::class);

        $this->commands([
            'command.hashid:key'
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 
    }
}
