<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\{
    ServiceContainer,
    PizzaType,
    OrderItem
};

class PizzaServiceTest extends TestCase
{
    public function testGetBySlug(): void
    {
        $this->migrate();
        $pizza = ServiceContainer::pizza()->getBySlug('chicago');
        $this->assertNotNull($pizza);
        $this->assertSame('Chicago', $pizza->name);
        $this->assertNull(ServiceContainer::pizza()->getBySlug('xxx'));
    }

    public function testGetDataForList(): void
    {
        $this->migrate();
        PizzaType::truncate();
        $two = new PizzaType();
        $two->name = 'Two Pizza';
        $two->slug = 'two';
        $two->price = 7.33;
        $two->save();
        $one = new PizzaType();
        $one->name = 'One Pizza';
        $one->slug = 'one';
        $one->price = 4.22;
        $one->save();
        $this->assertEquals([
            [
                'name' => 'One Pizza',
                'slug' => 'one',
                'prices' => [
                    'eur' => 422,
                    'usd' => 469,
                ],
                'photo' => 'http://pizza.loc/assets/img/pizza/one.png',
            ],
            [
                'name' => 'Two Pizza',
                'slug' => 'two',
                'prices' => [
                    'eur' => 733,
                    'usd' => 814,
                ],
                'photo' => 'http://pizza.loc/assets/img/pizza/two.png',
            ],
        ], ServiceContainer::pizza()->getDataForList());
    }

    public function testParseCart(): void
    {
        $this->migrate();
        $this->assertNull(ServiceContainer::pizza()->parseCart([
            'chicago' => 3,
            'greek' => 2,
            'xxx' => 1,
        ]), 'unknown pizza');
        $items = ServiceContainer::pizza()->parseCart([
            'chicago' => 3,
            'greek' => 2,
        ]);
        $this->assertCount(2, $items);
        $chicago = $items['chicago'];
        $this->assertInstanceOf(OrderItem::class, $chicago);
        $this->assertSame('Chicago', $chicago->getPizza()->name);
        $this->assertSame(3, $chicago->count);
        $this->assertEquals($chicago->getPizza()->price, $chicago->item_price);
    }
}
