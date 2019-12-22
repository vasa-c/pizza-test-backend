<?php

declare(strict_types=1);

namespace App\Events;

use App\Services\Orders\CheckoutResult;

class CheckoutEvent
{
    /**
     * @var CheckoutResult
     */
    public $result;

    public function __construct(CheckoutResult $result)
    {
        $this->result = $result;
    }
}
