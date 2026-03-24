<?php

namespace Plugins\IpBlocking;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Plugins\IpBlocking\Http\Middleware\EnforceIpAndUserBlocks;

class IpBlockingServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ip-blocking');
        $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');

        $router = $this->app->make('router');
        $router->pushMiddlewareToGroup('web', EnforceIpAndUserBlocks::class);
        $router->pushMiddlewareToGroup('api', EnforceIpAndUserBlocks::class);
    }

    public function activate(): void
    {
        Artisan::call('migrate', [
            '--path' => 'plugins/ip-blocking/database/migrations',
            '--force' => true,
        ]);
    }

    public function uninstall(): void
    {
        try {
            Schema::dropIfExists('blocked_ips');
        } catch (\Throwable) {
            //
        }
    }
}
