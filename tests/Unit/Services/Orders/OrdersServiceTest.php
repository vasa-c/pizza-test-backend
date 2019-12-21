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

    public function testGetPizzaPrice(): void
    {
        $this->migrate();
        $cart = [
            'chicago' => 2,
            'greek' => 1,
        ];
        $items = ServiceContainer::pizza()->parseCart($cart);
        $items['chicago']['pizza']->price = 2.23; // 2.48 $
        $items['greek']['pizza']->price = 3.34; // 3.71 $
        $this->assertEquals(7.8, ServiceContainer::orders()->getPizzaPrice($items, 'eur')); // 2.23 * 2 + 3.34
        $this->assertEquals(8.67, ServiceContainer::orders()->getPizzaPrice($items, 'usd'));
    }

    /**
     * @dataProvider providerGetDeliveryPrice
     * @param float $pizzaPrice
     * @param string $currency
     * @param float $expected
     */
    public function testGetDeliveryPrice(float $pizzaPrice, string $currency, float $expected): void
    {
        $this->assertEquals($expected, ServiceContainer::orders()->getDeliveryPrice($pizzaPrice, true, $currency));
        $this->assertEquals(0, ServiceContainer::orders()->getDeliveryPrice($pizzaPrice, false, $currency));
    }

    public function providerGetDeliveryPrice(): array
    {
        return [
            'cost' => [90, 'eur', 1],
            'free' => [110, 'eur', 0],
            'usd_cost' => [110, 'usd', 1.11], // 110 $ is 99 euro
            'usd_free' => [120, 'usd', 0],
        ];
    }
}
