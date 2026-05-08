<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\UpdateCheckoutDetailsRequest;
use App\Models\DeliveryType;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request): View
    {
        $order = Order::getCurrentOrder($request);
        $deliveryMethods = DeliveryType::all();
        return view('checkout', [
            'order' => $order,
            'isReview' => false,
            'deliveryMethods' => $deliveryMethods
        ]);
    }

    public function review(Request $request): View
    {
        $order = Order::getCurrentOrder($request);
        $deliveryMethods = DeliveryType::all();
        return view('checkout', [
            'order' => $order,
            'isReview' => true,
            'deliveryMethods' => $deliveryMethods
        ]);
    }

    public function payment(Request $request): View
    {
        $order = Order::getCurrentOrder($request);
        $paymentMethods = [
            'google' => 'Google Pay',
            'apple' => 'Apple Pay',
            'card' => 'Credit/Debit Card',
            'bank' => 'Bank transfer',
        ];

        return view('checkout.payment', [
            'order' => $order, 
            'paymentMethods' => $paymentMethods,
            'selectedPayment' => 'card',
        ]);
    }

    public function complete(Request $request): View
    {
        $order = Order::getCurrentOrder($request);
        return view('checkout.complete', ['order' => $order]);
    }

    public function confirm(Request $request): RedirectResponse
    {
        $order = Order::getCurrentOrder($request);
        $order->status = OrderStatus::Confirmed;
        $order->save();

        return redirect(route('checkout.payment'));
    }

    public function acceptPayment(Request $request): RedirectResponse
    {
        $order = Order::getCurrentOrder($request);
        $order->status = OrderStatus::Paid;
        $order->save();

        $user = auth()->user();
        if ($user) {
            // current order is complete, clear it out
            $user->current_order_id = null;
            $user->save();
        }

        return redirect(route('checkout.complete'));
    }

    public function setDetails(UpdateCheckoutDetailsRequest $request): RedirectResponse
    {
        $order = Order::getCurrentOrder($request);
        $validated = $request->validated();

        $order->contact_name = $validated['name'];
        $order->contact_phone = $validated['phone-number'];
        $order->contact_email = $validated['email'];

        $order->address_1 = $validated['address-1'];
        $order->address_2 = $validated['address-2'];
        $order->zip = $validated['zip'];
        $order->city = $validated['city'];
        $order->country = $validated['country'];

        $order->delivery_type_id = $validated['delivery-method'];

        $order->save();

        return redirect()->route('checkout.review');
    }
}
