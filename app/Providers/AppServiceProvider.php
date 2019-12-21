<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Users\{IUsersService, UsersService};
use App\Services\Pizza\{IPizzaService, PizzaService};

class AppServiceProvider extends ServiceProvider
{
    /**
     * {@inhertidoc}
     */
    public function register()
    {
        $app = $this->app;
        $app->singleton(IUsersService::class, UsersService::class);
        $app->singleton(IPizzaService::class, PizzaService::class);
    }

    /**
     * {@inhertidoc}
     */
    public function boot()
    {
    }
}
