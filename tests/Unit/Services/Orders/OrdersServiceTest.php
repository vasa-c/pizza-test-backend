<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\{
    ServiceContainer,
    Order,
    User
};

class OrderServiceTest extends TestCase
{
    public function testGetByNumber(): void
    {
        $this->migrate();
        /** @var Order $order */
        $order = factory(Order::class)->make();
        $order->setUser(factory(User::class)->create());
        $order->save();
        $order->createNumber();
        $order->save();
        $loaded = ServiceContainer::orders()->getByNumber($order->number);
        $this->assertTrue($order->is($loaded));
        $this->assertNull(ServiceContainer::orders()->getByNumber(2));
    }
}
