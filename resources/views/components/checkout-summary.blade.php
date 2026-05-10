<aside class="checkout-summary" aria-label="Order summary">
    <h2>Order summary</h2>

    <p class="price-row">
        <span>Subtotal</span><span id="subtotal-price">{{ $order->total_amount }} €</span>
    </p>
    <p class="price-row">
        <span>Delivery</span><span id="delivery-price">{{ $deliveryPrice }} €</span>
    </p>

    <p class="price-row total-price">
        <span>Total:</span><span id="total-price">{{ $total }} €</span>
    </p>

    <button type="submit" class="checkout-link">
        {{ $forwardText }}
    </button>

    <div class="checkout-link">
        <a href="{{ $backLink }}">Go back</a>
    </div>
</aside>
