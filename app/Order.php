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
        return $this->finalizedAs(self::STATUS_SUCCESS);
    }

    /**
     * @return bool
     */
    public function toFail(): bool
    {
        return $this->finalizedAs(self::STATUS_FAIL);
    }

    /**
     * @param string $status
     * @return bool
     */
    protected function finalizedAs(string $status): bool
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
        'user_id' => 'int',
        'user_created' => 'boolean',
        'outside' => 'boolean',
        'delivery_price' => 'float',
        'total_price' => 'float',
    ];
}
