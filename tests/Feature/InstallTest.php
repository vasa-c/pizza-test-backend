<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;

class InstallTest extends TestCase
{
    public function testAdminCreated()
    {
        $this->migrate();
        /** @var User $admin */
        $admin = User::where('email', 'admin@pizza.loc')->first();
        $this->assertNotNull($admin);
        $this->assertTrue($admin->isAdmin());
        $this->assertSame('Admin', $admin->name);
        $this->assertTrue($admin->validatePassword('admin'));
    }
}
