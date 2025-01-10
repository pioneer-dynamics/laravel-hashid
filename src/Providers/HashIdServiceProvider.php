<?php

namespace PioneerDynamics\LaravelHashid\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use PioneerDynamics\LaraveHashid\Console\GenerateHashIdKey;

class HashIdServiceProvider extends ServiceProvider implements DeferrableProvider
{
    const CONFIG_FILE = __DIR__.'/../../config/hashids.php';

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG_FILE, 'hashids');

        $this->app->bind('command.hashid:key', GenerateHashIdKey::class);

        $this->commands([
            'command.hashid:key'
        ]);
    }

    private function definePublishableComponents()
    {
        $this->publishes([
            self::CONFIG_FILE => config_path('hashids.php')
        ], 'laravel-hashid');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->definePublishableComponents();
    }
}
