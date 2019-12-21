<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use LogicException;

/**
 * @property int $id
 * @property int $number
 * @property int $user_id
 * @property string $user_name
 * @property string $email
 * @property string $address
 * @property string $contacts
 * @property string $currency
 * @property float $delivery_price
 * @property float $total_price
 * @property string $status
 * @property string $created_at
 * @property string $finalized_at
 */
class Order extends Model
{
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
     * {@inheritdoc}}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}}
     */
    protected $casts = [
        'user_id' => 'int',
        'delivery_price' => 'float',
        'total_price' => 'float',
    ];
}
