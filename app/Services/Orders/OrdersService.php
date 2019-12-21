<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Order;

class OrdersService implements IOrdersService
{
    /**
     * {@inheritDoc}
     */
    public function getByNumber(int $number): ?Order
    {
        return Order::where('number', $number)->first();
    }
}
