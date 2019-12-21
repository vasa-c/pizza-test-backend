<?php

declare(strict_types=1);

namespace App;

use App\Services\Users\IUsersService;

class ServiceContainer
{
    public static function users(): IUsersService
    {
        return app()->make(IUsersService::class);
    }
}
