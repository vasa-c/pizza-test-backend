<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\{
    Order,
    User
};

/**
 * The result of checkout can be:
 * - order created (with user created or no)
 * - required user login
 * - request is wrong
 * - another error
 */
class CheckoutResult
{
    /**
     * @var Order
     */
    public $order;

    /**
     * @var User
     */
    public $user;

    /**
     * Password of created user (for email) or NULL if user not created
     *
     * @var string
     */
    public $createdPassword;

    /**
     * @var bool
     */
    public $isLoginRequired = false;

    /**
     * @var bool
     */
    public $isRequestValid = false;

    /**
     * @var array
     */
    public $responseData;

    /**
     * @var int
     */
    public $responseCode;

    public function buildResponse(): void
    {
        $this->responseData = [];
        if ($this->order !== null) {
            $this->responseData['order_number'] = $this->order->number;
            if ($this->isUserCreated()) {
                $this->responseData['user'] = $this->user->getDataForFrontend();
            }
            $this->responseCode = 200;
            return;
        }
        if ($this->isLoginRequired) {
            $this->responseData['req_login'] = true;
            $this->responseCode = 200;
            return;
        }
        if (!$this->isRequestValid) {
            $this->responseCode = 422;
            return;
        }
        $this->responseCode = 500;
    }

    /**
     * @return bool
     */
    public function isUserCreated(): bool
    {
        return (($this->createdPassword !== null) && ($this->user !== null));
    }
}
