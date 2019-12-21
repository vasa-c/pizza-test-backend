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
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $order = factory(Order::class)->make();
        $order->setUser($user1);
        $this->assertSame($order->user_id, $user1->id);
        $this->assertTrue($user1->is($order->getUser()));
        $this->expectException(LogicException::class);
        $order->setUser($user2);
    }
}
