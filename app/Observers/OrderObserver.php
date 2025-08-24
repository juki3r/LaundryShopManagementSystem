<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\ExpoNotificationService;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if laundry_status changed to 'Approved'
        if ($order->isDirty('laundry_status') && $order->laundry_status === 'Approved') {
            $user = $order->user; // assuming Order belongsTo User
            if ($user && $user->expo_token) {
                $notifier = new ExpoNotificationService();
                $notifier->send(
                    $user->expo_token,
                    'Laundry Approved',
                    "Your laundry order #{$order->id} is approved!",
                    ['order_id' => $order->id]
                );
            }
        }
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
