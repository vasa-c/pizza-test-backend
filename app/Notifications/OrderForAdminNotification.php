<?php

declare(strict_types=1);

namespace App\Notifications;

class OrderForAdminNotification extends BaseOrderNotification
{
    /**
     * @var string
     */
    protected $template = 'mails.checkout.admin';

    /**
     * @return string
     */
    protected function getSubject(): string
    {
        return 'New pizza order (#'.$this->checkout->order->number.')';
    }
}
