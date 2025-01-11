<?php

namespace PioneerDynamics\LaravelHashid\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use PioneerDynamics\LaravelHashid\Console\GenerateHashIdKey;

class PioneerDynamicsHashIdServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->commands([
            GenerateHashIdKey::class
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
