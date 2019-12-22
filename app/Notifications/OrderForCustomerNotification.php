<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use App\Services\Orders\CheckoutResult;

class OrderForCustomerNotification extends BaseNotification
{
    /**
     * @param CheckoutResult $checkout
     */
    public function __construct(CheckoutResult $checkout)
    {
        parent::__construct();
        $this->checkout = $checkout;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Thanks for your order')
            ->view('mails.checkout.customer', ['checkout' => $this->checkout]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'checkout' => $this->checkout,
        ];
    }

    /**
     * @var CheckoutResult
     */
    private $checkout;
}
