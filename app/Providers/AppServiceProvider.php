<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Users\{IUsersService, UsersService};

class AppServiceProvider extends ServiceProvider
{
    /**
     * {@inhertidoc}
     */
    public function register()
    {
        $app = $this->app;
        $app->singleton(IUsersService::class, UsersService::class);
    }

    /**
     * {@inhertidoc}
     */
    public function boot()
    {
    }
}
