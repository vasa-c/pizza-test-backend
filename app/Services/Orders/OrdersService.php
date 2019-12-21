<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\{
    Order,
    PizzaType
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
    public function getPizzaPrice(array $items, string $currency): float
    {
        $price = 0;
        foreach ($items as $item) {
            /** @var PizzaType $pizza */
            $pizza = $item['pizza'];
            $count = $item['count'];
            $price += $pizza->getPrice($currency) * $count;
        }
        return $price;
    }
}
