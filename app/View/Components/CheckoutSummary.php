<?php

namespace App\View\Components;

use App\Models\Order;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CheckoutSummary extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Order $order, public string $forwardText, public string $backLink)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $deliveryPrice = $this->order->total_amount > 0
            ? ($this->order->deliveryType?->price ?? 0)
            : 0;
        $total = $this->order->total_amount + $deliveryPrice;

        return view('components.checkout-summary', compact('deliveryPrice', 'total'));
    }
}
