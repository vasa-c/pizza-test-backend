<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\{ServiceContainer, User, Order};
use Carbon\Carbon;
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

    public function testStatus(): void
    {
        $this->migrate();
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var Order $order */
        $order = factory(Order::class)->make();
        $order->setUser($user);
        $order->save();
        $this->assertSame(Order::STATUS_CREATED, $order->status);
        $this->assertFalse($order->isFinalized());
        $this->assertTrue($order->toDelivery());
        $this->assertFalse($order->toDelivery());
        $this->assertSame(Order::STATUS_DELIVERY, $order->status);
        $this->assertFalse($order->isFinalized());
        Carbon::setTestNow('2019-12-21 20:00:00');
        $this->assertNull($order->finalized_at);
        $this->assertTrue($order->toSuccess());
        Carbon::setTestNow('2019-12-21 20:10:00');
        $this->assertFalse($order->toSuccess());
        $this->assertFalse($order->toFail());
        $this->assertSame(Order::STATUS_SUCCESS, $order->status);
        $this->assertTrue($order->isFinalized());
        $this->assertSame('2019-12-21 20:00:00', $order->finalized_at);
    }

    public function testItemsAndPrices(): void
    {
        $this->migrate();
        $items = ServiceContainer::pizza()->parseCart([
            'chicago' => 2,
            'greek' => 1,
        ]);
        $items['chicago']->item_price = 2.2; // 2.44 $
        $items['greek']->item_price = 3.3; // 3.67 $
        /** @var Order $order */
        $order = factory(Order::class)->make([
            'currency' => 'usd',
            'outside' => true,
        ]);
        $order->setItems($items);
        $this->assertSame('usd', $items['chicago']->currency);
        $this->assertSame($items, $order->getItems());
        $order->calculatePrices();
        $this->assertEquals(1.11, $order->delivery_price);
        $this->assertEquals(9.66, $order->total_price);
        $order->save();
        $this->assertNull($items['chicago']->order_id);
        $order->saveItems();
        $this->assertSame($order->id, $items['chicago']->order_id);

        /** @var Order $loadedOrder */
        $loadedOrder = Order::find($order->id);
        $items = $loadedOrder->getItems();
        $this->assertCount(2, $items);
        $this->assertArrayHasKey('chicago', $items);
        $this->assertSame(2, $items['chicago']->count);
        $this->assertEquals(2.2, $items['chicago']->item_price);
    }
}
