<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function show(Order $order): View
    {
        $order->load(['deliveryType', 'products']);
        return view('order', compact('order'));
    }

    public function all(): View|RedirectResponse
    {
        if (auth()->guest()) {
            return redirect('/');
        }

        $user = auth()->user();
        $orders = $user->orders()
            ->where('total_amount', '>', 0)
            ->orderBy('created_at')
            ->get();
        return view('all-orders', compact('orders'));
    }
}
