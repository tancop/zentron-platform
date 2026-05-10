@php use App\Enums\OrderStatus; @endphp
    <!doctype html>
<html lang="en">
<head>
    <x-meta-tags title="order #{{ $order->id }}"/>
    @vite('resources/css/style.css')
    @vite('resources/css/order.css')
</head>

<body>

@include('components.header')

<main>
    <h1>Order #{{ $order->id }}</h1>

    <p class="order-status">
        @switch ($order->status)
            @case(OrderStatus::InCart)
                🛒 In Cart
                @break
            @case(OrderStatus::Confirmed)
                ✅ Confirmed
                @break
            @case(OrderStatus::Paid)
                💸 Paid
                @break
            @case(OrderStatus::Shipped)
                ✈️ Shipped
                @break
            @default
        @endswitch
    </p>


    <p>Created at: {{ $order->created_at }}</p>
    <p>Updated at: {{ $order->updated_at }}</p>

    <h2>Products</h2>

    <table class="product-tbl">
        <thead>
        <tr>
            <th>Product</th>
            <th>Amount</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->products as $product)
            <tr>
                <td>
                    <a class="black-link" href="{{ route('product', [$product]) }}">
                        {{ $product->name }}
                    </a>
                </td>
                <td>{{ $product->pivot->amount }}</td>
                <td>{{ $product->pivot->amount * $product->price }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p class="total-price">Total: {{ $order->total_amount }} €</p>

    @if ($order->status != OrderStatus::InCart->value)
        <h2>Shipping details</h2>
        <table class="details-box">
            <tbody>
            <tr>
                <td>Address:</td>
                <td>{{$order->address_1}}</td>
            </tr>
            @if ($order->address_2)
                <tr>
                    <td>Address 2:</td>
                    <td>{{$order->address_2}}</td>
                </tr>
            @endif
            <tr>
                <td>Postal Code:</td>
                <td>{{$order->zip}}</td>
            </tr>
            @if ($order->city)
                <tr>
                    <td>City:</td>
                    <td>{{$order->city}}</td>
                </tr>
            @endif
            <tr>
                <td>Country:</td>
                <td>{{$order->country}}</td>
            </tr>
            </tbody>
        </table>

        <h2>Contact info</h2>
        <table class="details-box">
            <tbody>
            <tr>
                <td>Name:</td>
                <td>{{ $order->contact_name }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $order->contact_email }}</td>
            </tr>
            @if ($order->contact_phone)
                <tr>
                    <td>Phone:</td>
                    <td>{{ $order->contact_phone }}</td>
                </tr>
            @endif
            </tbody>
        </table>

        @if ($order->deliveryType)
            <h2>Delivery</h2>
            <table class="details-box">
                <tbody>
                <tr>
                    <td>{{ $order->deliveryType->name }}</td>
                    <td>{{ $order->deliveryType->price }} €</td>
                </tr>
                </tbody>
            </table>
        @endif
    @endif
</main>

@include('components.footer')

@include('components.mobile-nav')

</body>
</html>
