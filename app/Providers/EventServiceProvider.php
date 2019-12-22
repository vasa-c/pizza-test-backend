<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CheckoutEvent;
use App\Listeners\CheckoutListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CheckoutEvent::class => [CheckoutListener::class],
    ];

    public function boot()
    {
        parent::boot();
    }
}
