<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\{
    User,
    PizzaType
};

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

    public function testPizzaCreated()
    {
        $this->migrate();
        $pizza = PizzaType::where('slug', 'new-york-style')->first();
        $this->assertNotNull($pizza);
        $this->assertSame('New York-Style', $pizza->name);
    }
}
