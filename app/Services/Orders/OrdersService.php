<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\{
    Order,
    PizzaType,
    Price,
};

class OrdersService implements IOrdersService
{
    /**
     * {@inheritDoc}
     */
    public function getByNumber(int $number): ?Order
    {
        return Order::where('number', $number)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function calculatePizzaPrice(array $items, string $currency): float
    {
        $price = 0;
        foreach ($items as $item) {
            $item->currency = $currency;
            $price += $item->calculateTotalPrice();
        }
        return $price;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateDeliveryPrice(float $pizzaPrice, bool $outside, string $currency): float
    {
        if (!$outside) {
            return 0;
        }
        $pizzaPriceEuro = Price::convert($pizzaPrice, Price::CURRENCY_EURO, $currency);
        if ($pizzaPriceEuro >= 100) {
            return 0;
        }
        return Price::convert(1, $currency);
    }
}
