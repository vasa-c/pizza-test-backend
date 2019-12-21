<?php

declare(strict_types=1);

namespace App;

class Price
{
    public const CURRENCY_EURO = 'eur';
    public const CURRENCY_USD = 'usd';

    /**
     * @return string
     */
    public static function getDefaultCurrency(): string
    {
        return self::CURRENCY_EURO;
    }

    /**
     * Returns rate a currency to euro
     *
     * @param string $currency
     * @return float
     */
    public static function getRate(string $currency): float
    {
        if ($currency === self::CURRENCY_USD) {
            return (float)config('pizza.usd_rate');
        }
        return 1;
    }
}
