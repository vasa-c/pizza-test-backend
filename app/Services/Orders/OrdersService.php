<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\{
    Order,
    Price,
    User
};
use App\Http\Requests\CheckoutRequest;
use App\Events\CheckoutEvent;

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
    public function calculatePizzaPrice(array $items): float
    {
        $price = 0;
        foreach ($items as $item) {
            $price += $item->calculateTotalPrice();
        }
        return round($price, 2);
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

    /**
     * {@inheritdoc}
     */
    public function checkout(CheckoutRequest $request, ?User $user): CheckoutResult
    {
        $process = new CheckoutProcess($request, $user);
        $result = $process->process();
        $result->buildResponse();
        if ($result->order !== null) {
            event(new CheckoutEvent($result));
        }
        return $result;
    }
}
