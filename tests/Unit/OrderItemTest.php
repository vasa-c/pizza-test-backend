<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\{
    Order,
    OrderItem,
    ServiceContainer
};
use LogicException;

class OrderItemTest extends TestCase
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
        $this->assertSame('usd', $item->currency);
    }

    public function testSetGetPizza(): void
    {
        $this->migrate();
        $pizza = ServiceContainer::pizza()->getBySlug('chicago');
        $pizza->price = 2.23;
        $item = new OrderItem();
        $this->assertNull($item->getPizza());
        $item->setPizza($pizza, 3);
        $this->assertSame($pizza->id, $item->pizza_type_id);
        $this->assertTrue($pizza->is($item->getPizza()));
        $this->assertEquals(2.23, $item->item_price);
        $this->assertSame(3, $item->count);
    }

    public function testCalculateTotalPrice(): void
    {
        $item = new OrderItem();
        $item->currency = 'usd';
        $item->item_price = 2.22;
        $item->count = 2;
        $this->assertEquals(4.93, $item->calculateTotalPrice());
        $this->assertEquals(4.93, $item->total_price);
        $item->item_price = null;
        $this->expectException(LogicException::class);
        $item->calculateTotalPrice();
    }
}
