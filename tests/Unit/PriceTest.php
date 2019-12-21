<?php

declare(strict_types=1);

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

    /**
     * @dataProvider providerConvert
     * @param float $price
     * @param string $to
     * @param string|null $from
     * @param float $expected
     */
    public function testConvert(float $price, string $to, ?string $from, float $expected): void
    {
        $this->assertEquals($expected, Price::convert($price, $to, $from));
    }

    /**
     * @return array
     */
    public function providerConvert(): array
    {
        return [
            [10.12, 'usd', 'eur', 11.24],
            [10.12, 'usd', null, 11.24],
            [10.12, 'eur', 'usd', 9.11],
            [10.12, 'usd', 'usd', 10.12],
            [10.12, 'eur', 'eur', 10.12],
        ];
    }

    public function testToFrontend(): void
    {
        $this->assertSame(124, Price::toFrontend(1.237));
        $this->assertSame(0, Price::toFrontend(0));
        $this->assertSame(300, Price::toFrontend(3));
    }
}
