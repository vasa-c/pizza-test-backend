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
}
