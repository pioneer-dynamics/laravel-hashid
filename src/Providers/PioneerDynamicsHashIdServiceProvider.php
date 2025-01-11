<?php

namespace PioneerDynamics\LaravelHashid\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use PioneerDynamics\LaravelHashid\Console\GenerateHashIdKey;

class PioneerDynamicsHashIdServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // 
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->commands([
            GenerateHashIdKey::class
        ]);
    }
}
