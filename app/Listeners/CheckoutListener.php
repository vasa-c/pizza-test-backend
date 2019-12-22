<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\CheckoutEvent;
use App\Notifications\{
    OrderForCustomerNotification,
    OrderForAdminNotification
};
use Illuminate\Support\Facades\Notification;

class CheckoutListener extends BaseListener
{
    /**
     * @param CheckoutEvent $event
     */
    public function handle(CheckoutEvent $event)
    {
        $this->notifyCustomer($event);
        $this->notifyAdmin($event);
    }

    /**
     * @param CheckoutEvent $event
     */
    private function notifyCustomer(CheckoutEvent $event): void
    {
        $ntf = new OrderForCustomerNotification($event->result);
        $event->result->user->notify($ntf);
    }

    /**
     * @param CheckoutEvent $event
     */
    private function notifyAdmin(CheckoutEvent $event): void
    {
        $ntf = new OrderForAdminNotification($event->result);
        $route = Notification::route('mail', config('pizza.adminEmail'));
        Notification::send($route, $ntf);
    }
}
