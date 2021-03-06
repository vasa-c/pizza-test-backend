<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\{
    Order,
    OrderItem,
    User
};
use App\Http\Requests\CheckoutRequest;

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
     * @param OrderItem[] $items
     * @return mixed
     */
    public function calculatePizzaPrice(array $items): float;

    /**
     * Returns the delivery cost for the order
     *
     * @param float $pizzaPrice
     * @param bool $outside
     * @param string $currency
     * @return float
     */
    public function calculateDeliveryPrice(float $pizzaPrice, bool $outside, string $currency): float;

    /**
     * Checkout process and create order
     *
     * @param CheckoutRequest $request
     * @param User|null $user
     * @return CheckoutResult
     */
    public function checkout(CheckoutRequest $request, ?User $user): CheckoutResult;
}
