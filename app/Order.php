<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use LogicException;

/**
 * @property int $id
 * @property int $number
 * @property int $user_id
 * @property string $user_name
 * @property bool $user_created
 * @property string $email
 * @property string $address
 * @property string $contacts
 * @property string $currency
 * @property bool $outside
 * @property float $delivery_price
 * @property float $total_price
 * @property string $status
 * @property string $created_at
 * @property string $finalized_at
 */
class Order extends Model
{
    public const STATUS_CREATED = 'created';
    public const STATUS_DELIVERY = 'delivery';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';

    /**
     * Creates the order number for fronted
     */
    public function createNumber(): void
    {
        if ($this->id === null) {
            throw new LogicException('number generator require id');
        }
        $this->number = 1234 + $this->id * 7;
    }

    /**
     * Set the customer
     *
     * @param User $user
     */
    public function setUser(User $user): void
    {
        if ($this->user_id !== null) {
            throw new LogicException('order.user already set');
        }
        $this->user_id = $user->id;
    }

    /**
     * Returns the customer
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->user_id === null) {
            return null;
        }
        return User::find($this->user_id);
    }

    /**
     * @return bool
     */
    public function isFinalized(): bool
    {
        return in_array($this->status, [self::STATUS_SUCCESS, self::STATUS_FAIL]);
    }

    /**
     * @return bool
     */
    public function toDelivery(): bool
    {
        if ($this->status !== self::STATUS_CREATED) {
            return false;
        }
        $this->status = self::STATUS_DELIVERY;
        $this->save();
        return true;
    }

    /**
     * @return bool
     */
    public function toSuccess(): bool
    {
        return $this->finalizeAs(self::STATUS_SUCCESS);
    }

    /**
     * @return bool
     */
    public function toFail(): bool
    {
        return $this->finalizeAs(self::STATUS_FAIL);
    }

    /**
     * @param OrderItem[] $items
     */
    public function setItems(array $items): void
    {
        if ($this->id !== null) {
            throw new LogicException('setItems() for created order');
        }
        $currency = $this->currency;
        foreach ($items as $item) {
            $item->currency = $currency;
        }
        $this->items = $items;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems(): array
    {
        if ($this->id === null) {
            return $this->items ?? [];
        }
        $items = [];
        /** @var OrderItem $item */
        foreach (OrderItem::where('order_id', $this->id)->orderBy('id', 'asc')->get() as $item) {
            $pizza = $item->getPizza();
            if ($pizza !== null) {
                $items[$pizza->slug] = $item;
            }
        }
        return $items;
    }

    public function saveItems(): void
    {
        if ($this->id === null) {
            throw new LogicException('saveItems() for not created order');
        }
        if ($this->items === null) {
            throw new LogicException('saveItems() for empty items');
        }
        foreach ($this->items as $item) {
            $item->setOrder($this);
            $item->save();
        }
    }

    /**
     * Calculates delivery and total prices
     */
    public function calculatePrices(): void
    {
        $orders = ServiceContainer::orders();
        $pizzaPrice = $orders->calculatePizzaPrice($this->items);
        $deliveryPrice = $orders->calculateDeliveryPrice($pizzaPrice, $this->outside, $this->currency);
        $this->delivery_price = $deliveryPrice;
        $this->total_price = $pizzaPrice + $deliveryPrice;
    }

    /**
     * @return string
     */
    public function getCabinetLink(): string
    {
        return url('/cabinet/'.$this->number); // Laravel has not route for frontend page
    }

    /**
     * @return string
     */
    public function getAdminLink(): string
    {
        return url('/admin/'.$this->number);
    }

    /**
     * @return array
     */
    public function getDataForList(): array
    {
        return [
            'number' => $this->number,
            'status' => $this->status,
            'total_price' => Price::toFrontend($this->total_price),
            'currency' => $this->currency,
            'created_at' => $this->created_at,
            'finalized_at' => $this->finalized_at
        ];
    }

    /**
     * @return array
     */
    public function getDataForPage(): array
    {
        $items = [];
        foreach ($this->getItems() as $item) {
            $items[] = $item->getDataForOrderPage();
        }
        return array_replace($this->getDataForList(), [
            'user_name' => $this->user_name,
            'email' => $this->email,
            'address' => $this->address,
            'contacts' => $this->contacts,
            'outside' => $this->outside,
            'delivery_price' => Price::toFrontend($this->delivery_price),
            'items' => $items,
        ]);
    }

    /**
     * @param string $status
     * @return bool
     */
    protected function finalizeAs(string $status): bool
    {
        if ($this->isFinalized()) {
            return false;
        }
        $this->status = $status;
        $this->finalized_at = Carbon::now()->format('Y-m-d H:i:s');
            $this->save();
        return true;
    }

    /**
     * {@inheritdoc}}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}}
     */
    protected $casts = [
        'number' => 'int',
        'user_id' => 'int',
        'user_created' => 'boolean',
        'outside' => 'boolean',
        'delivery_price' => 'float',
        'total_price' => 'float',
    ];

    /**
     * Items before save
     *
     * @var OrderItem[]
     */
    private $items;
}
