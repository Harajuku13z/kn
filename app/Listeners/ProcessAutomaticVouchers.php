<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Http\Controllers\Admin\AutomaticVoucherController;

class ProcessAutomaticVouchers
{
    protected $automaticVoucherController;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AutomaticVoucherController $automaticVoucherController)
    {
        $this->automaticVoucherController = $automaticVoucherController;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        
        // Traiter les bons automatiques pour cette commande
        if ($order->user_id) {
            $this->automaticVoucherController->processAutomaticVouchers(
                $order->user_id,
                $order->id
            );
        }
    }
}
