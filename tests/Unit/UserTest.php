<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\{
    User,
    ServiceContainer
};

class UserTest extends TestCase
{
    public function testCreate(): void
    {
        $this->migrate();
        $user = new User();
        $user->email = 'a@example.com';
        $user->password = 'xxx';
        $user->name = 'Tester';
        $user->save();
        $user->refresh();
        $this->assertFalse($user->isAdmin());
        $user->is_admin = true;
        $this->assertTrue($user->isAdmin());
    }

    public function testSetPassword(): void
    {
        $user = new User();
        $this->assertFalse($user->validatePassword('my-password'));
        $user->setPassword('my-password');
        $this->assertTrue($user->validatePassword('my-password'));
        $this->assertFalse($user->validatePassword('not-my-password'));
        $this->assertTrue(ServiceContainer::users()->passwordValidate('my-password', $user->getAttribute('password')));
    }
}
