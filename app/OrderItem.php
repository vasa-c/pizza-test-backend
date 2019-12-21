<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use LogicException;

/**
 * @property int $id
 * @property int $order_id
 * @property int $pizza_type_id
 * @property int $count
 * @property string $currency
 * @property float $total_price
 * @property float $item_price
 */
class OrderItem extends Model
{
    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order_id = $order->id;
        $this->currency = $order->currency;
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return Order::find($this->order_id);
    }

    /**
     * @param PizzaType $pizza
     * @param int $count
     */
    public function setPizza(PizzaType $pizza, int $count = 1): void
    {
        $this->pizza_type_id = $pizza->id;
        $this->item_price = $pizza->price;
        $this->count = $count;
    }

    /**
     * @return PizzaType|null
     */
    public function getPizza(): ?PizzaType
    {
        return PizzaType::find($this->pizza_type_id);
    }

    /**
     * @return float
     */
    public function calculateTotalPrice(): float
    {
        $itemPrice = $this->item_price;
        $currency = $this->currency;
        $count = $this->count;
        if ($itemPrice === null) {
            throw new LogicException('Need item_price for total_price');
        }
        if ($currency === null) {
            throw new LogicException('Need currency for total_price');
        }
        if ($count === null) {
            throw new LogicException('Need count for total_price');
        }
        $total = Price::convert($itemPrice * $count, $this->currency);
        $this->total_price = $total;
        return $total;
    }

    /**
     * {@inheritdoc}}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}}
     */
    protected $casts = [
        'user_id' => 'int',
        'pizza_type_id' => 'int',
        'total_price' => 'float',
        'item_price' => 'float',
    ];
}
