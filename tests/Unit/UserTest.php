<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;

class UserTest extends TestCase
{
    public function testCreate()
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
}
