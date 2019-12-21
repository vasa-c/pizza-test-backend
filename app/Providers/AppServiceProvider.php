<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Users\{IUsersService, UsersService};
use App\Services\Pizza\{IPizzaService, PizzaService};
use App\Services\Orders\{IOrdersService, OrdersService};

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
        $app->singleton(IOrdersService::class, OrdersService::class);
    }

    /**
     * {@inhertidoc}
     */
    public function boot()
    {
    }
}
