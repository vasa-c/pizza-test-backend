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

    public function testCalculateGetPizzaPrice(): void
    {
        $this->migrate();
        $cart = [
            'chicago' => 2,
            'greek' => 1,
        ];
        $items = ServiceContainer::pizza()->parseCart($cart);
        $items['chicago']->item_price = 2.23; // 2.48 $
        $items['greek']->item_price = 3.34; // 3.71 $
        $this->assertEquals(7.8, ServiceContainer::orders()->calculatePizzaPrice($items, 'eur')); // 2.23 * 2 + 3.34
        $this->assertSame('eur', $items['greek']->currency);
        $this->assertEquals(8.67, ServiceContainer::orders()->calculatePizzaPrice($items, 'usd'));
        $this->assertSame('usd', $items['greek']->currency);
    }

    /**
     * @dataProvider providerGetDeliveryPrice
     * @param float $pizzaPrice
     * @param string $currency
     * @param float $expected
     */
    public function calculateGetDeliveryPrice(float $pizzaPrice, string $currency, float $expected): void
    {
        $this->assertEquals($expected, ServiceContainer::orders()->calculateDeliveryPrice($pizzaPrice, true, $currency));
        $this->assertEquals(0, ServiceContainer::orders()->calculateDeliveryPrice($pizzaPrice, false, $currency));
    }

    public function providerCalculateDeliveryPrice(): array
    {
        return [
            'cost' => [90, 'eur', 1],
            'free' => [110, 'eur', 0],
            'usd_cost' => [110, 'usd', 1.11], // 110 $ is 99 euro
            'usd_free' => [120, 'usd', 0],
        ];
    }
}
