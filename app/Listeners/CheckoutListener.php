<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\CheckoutEvent;
use App\Notifications\OrderForCustomerNotification;

class CheckoutListener extends BaseListener
{
    public function handle(CheckoutEvent $event)
    {
        $toCustomer = new OrderForCustomerNotification($event->result);
        $event->result->user->notify($toCustomer);
    }
}
