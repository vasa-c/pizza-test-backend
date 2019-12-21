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
}
