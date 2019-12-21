<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Price;

class PriceTest extends TestCase
{
    public function testGetRate(): void
    {
        $this->assertEquals(1, Price::getRate(Price::CURRENCY_EURO));
        $this->assertEquals(0.9, Price::getRate(Price::CURRENCY_USD));
        config()->set('pizza.usd_rate', 0.8);
        $this->assertEquals(1, Price::getRate(Price::CURRENCY_EURO));
        $this->assertEquals(0.8, Price::getRate(Price::CURRENCY_USD));
    }
}
