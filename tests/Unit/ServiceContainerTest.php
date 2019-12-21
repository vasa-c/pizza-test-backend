<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\ServiceContainer;
use App\Services\Users\UsersService;

class ServiceContainerTest extends TestCase
{
    public function testCreate(): void
    {
        $users = ServiceContainer::users();
        $this->assertInstanceOf(UsersService::class, $users);
        $this->assertSame($users, ServiceContainer::users());
    }
}
