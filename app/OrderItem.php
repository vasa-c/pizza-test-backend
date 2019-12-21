<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

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
     */
    public function setPizza(PizzaType $pizza): void
    {
        $this->pizza_type_id = $pizza->id;
    }

    /**
     * @return PizzaType|null
     */
    public function getPizza(): ?PizzaType
    {
        return PizzaType::find($this->pizza_type_id);
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
