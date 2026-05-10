<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use App\Enums\OrderStatus;

class MergeGuestCartOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $orderId = Session::get("orderId");
        
        if ($orderId) {
            $guestOrder = Order::find($orderId);
            
            if ($guestOrder && $guestOrder->status === OrderStatus::InCart) {
                $user = $event->user;
                $userOrder = $user->currentOrder;
                
                if ($userOrder == null) {
                    $userOrder = new Order;
                    $userOrder->status = OrderStatus::InCart;
                    $userOrder->total_amount = 0;
                    $userOrder->user_id = $user->id;
                    $userOrder->save();

                    $user->current_order_id = $userOrder->id;
                    $user->save();
                }
                
                $userOrder->mergeOrder($guestOrder);
                
                $guestOrder->products()->detach();
                $guestOrder->delete();
            }
            
            Session::forget("orderId");
        }
    }
}
