<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\PizzaType;

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
            'price' => 7.2,
        ], $pizza->getShortData());
        $this->assertEquals([
            'name' => 'My',
            'slug' => 'my',
            'photo' => 'http://pizza.loc/assets/img/pizza/my.png',
            'price' => 7.2,
            'description' => 'This is my pizza',
        ], $pizza->getFullData());
    }
}
