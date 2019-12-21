<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\{
    ServiceContainer,
    PizzaType,
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
                'price' => 4.22,
                'photo' => 'http://pizza.loc/assets/img/pizza/one.png',
            ],
            [
                'name' => 'Two Pizza',
                'slug' => 'two',
                'price' => 7.33,
                'photo' => 'http://pizza.loc/assets/img/pizza/two.png',
            ],
        ], ServiceContainer::pizza()->getDataForList());
    }
}
