<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\ServiceContainer;
use Illuminate\Support\Facades\Hash;

class UserServiceTest extends TestCase
{
    public function testPasswordHashAndValidate(): void
    {
        $users = ServiceContainer::users();
        $hashOne = $users->passwordHash('one');
        $this->assertTrue($users->passwordValidate('one', $hashOne));
        $this->assertFalse($users->passwordValidate('two', $hashOne));
        $this->assertTrue($users->passwordValidate('two', Hash::make('two')));
        $this->assertFalse($users->passwordValidate('two', Hash::make('one')));
        $this->assertTrue(Hash::check('one', $hashOne));
    }
}
