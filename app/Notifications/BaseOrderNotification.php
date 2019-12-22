<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use App\Services\Orders\CheckoutResult;

abstract class BaseOrderNotification extends BaseNotification
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
            ->subject($this->getSubject())
            ->from(config('pizza.adminEmail'))
            ->view($this->template, ['checkout' => $this->checkout]);
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
     * @var string
     */
    protected $template;

    /**
     * @return string
     */
    abstract protected function getSubject(): string;

    /**
     * @var CheckoutResult
     */
    protected $checkout;
}
