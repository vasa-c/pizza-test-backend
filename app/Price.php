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
            return max((float)config('pizza.usd_rate'), 0.01);
        }
        return 1;
    }

    /**
     * Currency convert
     *
     * @param float $price
     * @param string $to
     * @param string|null $from [optional]
     * @return float
     */
    public static function convert(float $price, string $to, ?string $from = null): float
    {
        if ($from === null) {
            $from = self::getDefaultCurrency();
        }
        if ($to === $from) {
            $result = $price;
        } elseif ($from === self::CURRENCY_EURO) {
            $result = $price / self::getRate(self::CURRENCY_USD);
        } else {
            $result = $price * self::getRate(self::CURRENCY_USD);
        }
        return round($result, 2);
    }

    /**
     * Convert price to frontend format
     *
     * @param float $price
     * @return int
     */
    public static function toFrontend(float $price): int
    {
        return (int)round($price * 100);
    }

    /**
     * @param float $price
     *        price in euro
     * @return array
     */
    public static function getPricesForFrontend(float $price): array
    {
        $result = [];
        foreach (self::getListCurrencies() as $currency) {
            $result[$currency] = self::toFrontend(self::convert($price, $currency));
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getListCurrencies(): array
    {
        return [self::CURRENCY_EURO, self::CURRENCY_USD];
    }

    /**
     * @return array
     */
    public static function getListCurrenciesForFrontend(): array
    {
        $result = [];
        foreach (self::getListCurrencies() as $key) {
            $result[] = [
                'key' => $key,
                'label' => self::$labels[$key] ?? $key,
            ];
        }
        return $result;
    }

    /**
     * @return string
     */
    public static function getCurrencyValidationRule(): string
    {
        return 'string|in:'.implode(',', self::getListCurrencies());
    }

    /**
     * @var string[]
     */
    private static $labels = [
        self::CURRENCY_EURO => "\u{20AC}",
        self::CURRENCY_USD => '$',
    ];
}
