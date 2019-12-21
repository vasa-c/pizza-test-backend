<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\{
    User,
    Order
};
use LogicException;

class OrderTest extends TestCase
{
    public function testSetGetUser(): void
    {
        $this->migrate();
        $user = new User();
        $user->email = 'a@example.com';
        $user->setPassword('xxx');
        $user->name = 'Tester';
        $user->save();
        $order = new Order();
        $this->assertNull($order->getUser());
        $order->setUser($user);
        $this->assertSame($order->user_id, $user->id);
        $this->assertTrue($user->is($order->getUser()));
        $this->expectException(LogicException::class);
        $order->setUser($user);
    }
}
