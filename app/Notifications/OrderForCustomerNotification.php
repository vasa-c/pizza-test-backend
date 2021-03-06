<?php

declare(strict_types=1);

namespace App\Notifications;

class OrderForCustomerNotification extends BaseOrderNotification
{
    /**
     * @var string
     */
    protected $template = 'mails.checkout.customer';

    /**
     * @return string
     */
    protected function getSubject(): string
    {
        return 'Thanks for your order (#'.$this->checkout->order->number.')';
    }
}
