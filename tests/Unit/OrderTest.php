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
        /** @var User $user1 */
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        /** @var Order $order */
        $order = factory(Order::class)->make();
        $order->setUser($user1);
        $this->assertSame($order->user_id, $user1->id);
        $this->assertTrue($user1->is($order->getUser()));
        $this->expectException(LogicException::class);
        $order->setUser($user2);
    }

    public function testCreateNumber(): void
    {
        $this->migrate();
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var Order $order */
        $order = factory(Order::class)->make();
        $order->setUser($user);
        $order->save();
        $this->assertNull($order->number);
        $order->createNumber();
        $this->assertGreaterThan(1000, $order->number);
    }
}
