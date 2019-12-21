<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Order;

interface IOrdersService
{
    /**
     * Returns order by a number
     *
     * @param int $number
     * @return Order|null
     */
    public function getByNumber(int $number): ?Order;

    /**
     * Returns sum price of all pizza in order in the specified currency
     *
     * @param array $items
     * @param string $currency
     * @return mixed
     */
    public function getPizzaPrice(array $items, string $currency): float;

    /**
     * Returns the delivery cost for the order
     *
     * @param float $pizzaPrice
     * @param bool $outside
     * @param string $currency
     * @return float
     */
    public function getDeliveryPrice(float $pizzaPrice, bool $outside, string $currency): float;
}
