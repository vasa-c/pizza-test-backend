<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\{
    Order,
    OrderItem,
    ServiceContainer
};

class OrderTest extends TestCase
{
    public function testSetGetOrder(): void
    {
        $this->migrate();
        $order = factory(Order::class)->create([
            'currency' => 'usd',
        ]);
        $item = new OrderItem();
        $this->assertNull($item->getOrder());
        $item->setOrder($order);
        $this->assertSame($order->id, $item->order_id);
        $this->assertTrue($order->is($item->getOrder()));
    }

    public function testSetGetPizza(): void
    {
        $this->migrate();
        $pizza = ServiceContainer::pizza()->getBySlug('chicago');
        $item = new OrderItem();
        $this->assertNull($item->getPizza());
        $item->setPizza($pizza);
        $this->assertSame($pizza->id, $item->pizza_type_id);
        $this->assertTrue($pizza->is($item->getPizza()));
    }
}
