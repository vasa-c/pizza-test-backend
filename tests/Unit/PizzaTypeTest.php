<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\{
    PizzaType,
    Price
};

class PizzaTypeTest extends TestCase
{
    /**
     * covers ::getShortData
     * covers ::getFullData
     */
    public function testGetData(): void
    {
        $this->migrate();
        $pizza = new PizzaType();
        $pizza->name = 'My';
        $pizza->slug = 'my';
        $pizza->description = 'This is my pizza';
        $pizza->price = 7.2;
        $pizza->save();
        $this->assertEquals([
            'name' => 'My',
            'slug' => 'my',
            'photo' => 'http://pizza.loc/assets/img/pizza/my.png',
            'prices' => [
                'eur' => 720,
                'usd' => 800,
            ],
        ], $pizza->getShortData());
        $this->assertEquals([
            'name' => 'My',
            'slug' => 'my',
            'photo' => 'http://pizza.loc/assets/img/pizza/my.png',
            'prices' => [
                'eur' => 720,
                'usd' => 800,
            ],
            'description' => 'This is my pizza',
        ], $pizza->getFullData());
    }

    public function testGetPrice(): void
    {
        $pizza = new PizzaType();
        $pizza->price = 9.99;
        $this->assertEquals(9.99, $pizza->getPrice(Price::CURRENCY_EURO));
        $this->assertEquals(11.1, $pizza->getPrice(Price::CURRENCY_USD));
    }
}
