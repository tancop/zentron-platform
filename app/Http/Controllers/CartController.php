<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCartAmountRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(Request $request): View
    {
        $order = Order::getCurrentOrder($request);

        $products = $order->products;

        $entries = [];
        foreach ($products as $product) {
            /* @var $product Product */
            $entries[$product->id] = $product->pivot->amount;
        }

        $deliveryFee = $order->deliveryType?->price;

        return view('cart', [
            'order' => $order,
            'products' => $products,
            'amounts' => $entries,
            'totalPrice' => $order->totalPrice(),
            'deliveryFee' => $deliveryFee,
        ]);
    }

    public function setAmount(UpdateCartAmountRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        /* @var $id int */
        $id = $validated['id'];
        /* @var $amount int */
        $amount = $validated['amount'];

        $order = Order::getCurrentOrder($request);

        // add OrderProduct instance to database
        $order->products()->syncWithoutDetaching([$id => ['amount' => $amount]]);

        $order->updateTotalAmount();

        return redirect()->route('cart');
    }

    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:Product,id',
        ]);
        /* @type $id int */
        $id = $validated['id'];

        $order = Order::getCurrentOrder($request);

        // add OrderProduct instance to database
        $order->products()->detach([$id]);
        $order->updateTotalAmount();

        return redirect()->route('cart');
    }
}
