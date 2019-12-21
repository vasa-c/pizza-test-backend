<?php

declare(strict_types=1);

namespace App;

use App\Services\Users\IUsersService;
use App\Services\Pizza\IPizzaService;

class ServiceContainer
{
    public static function users(): IUsersService
    {
        return app()->make(IUsersService::class);
    }

    public static function pizza(): IPizzaService
    {
        return app()->make(IPizzaService::class);
    }
}
