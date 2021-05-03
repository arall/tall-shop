<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Users\Orders\Confirmation;
use App\Notifications\Users\Orders\InPreparation;
use App\Notifications\Users\Orders\Shipped;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        // Status change
        if ($order->isDirty('status')) {
            switch ($order->status) {
                    // Payment Completed
                case 1:
                    Notification::send($order->user, new Confirmation($order));
                    break;

                    // In preparation
                case 2:
                    Notification::send($order->user, new InPreparation($order));
                    break;

                    // Shipped
                case 3:
                    Notification::send($order->user, new Shipped($order));
                    break;
            }
        }
    }
}
