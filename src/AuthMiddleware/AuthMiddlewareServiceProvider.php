<?php

namespace Cego\AuthMiddleware;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class AuthMiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(RemoteUserAuthentication::class);
    }
}
