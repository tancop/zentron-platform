<!doctype html>
<html lang="en">
<head>
    <x-meta-tags :title="$product->name"
                 :description="'product page for '.$product->name"/>
    @vite('resources/css/style.css')
    @vite('resources/css/product.css')
    @vite('resources/js/product-amount.ts')
    @vite('resources/js/product-image-cycle.ts')
</head>

<body>
@include('components.header')

<main>
    <div class="main-product">
        <x-product-gallery :product="$product" :imageUrls="$imageUrls" :avifUrls="$avifUrls" />
        <div class="product-col">
            <h1>{{ $product->name }}</h1>
            @if ($product->brand != null)
                <a class="black-link brand-name" href="/brand/{{ $product->brand->id }}">
                    {{ $product->brand->name }}<span aria-hidden="true"> > </span>
                </a>
            @endif

            <div class="tags">
                @foreach ($product->categories as $category)
                    <a class="tag black-link" href="/category/{{ $category->id }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
            <div class="spacer" aria-hidden="true"></div>
            <p class="main-price">{{ $product->price }} €</p>
        </div>
        <div class="product-col">
            @can ('update', $product)
                <a class="black-link cart-btn" href="{{ route('product.edit', [$product]) }}">Edit this product</a>
            @endcan

            <h2>Delivery from {{ \App\Models\DeliveryType::min('price') }} €</h2>
            <p>Expected {{ Date::now()->addDays(3)->format('M d') }} - {{ Date::now()->addDays(6)->format('M d') }}</p>

            <form class="cart-row" method="post" action="/cart/setAmount">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}"/>
                <button type="button" id="amount-minus" class="cart-btn">-</button>
                <input class="amount-field" id="amount-field" type="number" name="amount" value="1" min="1"/>
                <button type="button" id="amount-plus" class="cart-btn">+</button>
                <div class="cart-row-spacer" aria-hidden="true"></div>
                <button type="submit" class="cart-btn">Add to cart</button>
            </form>
        </div>
    </div>

    <div class="product-desc">
        <h2>Description</h2>
        <pre>{{ $product->description }}</pre>
    </div>
</main>

<div class="products">
    @foreach ($otherProducts as $product)
        <x-product-card :product="$product"/>
    @endforeach
</div>


@include('components.footer')

@include('components.mobile-nav')
</body>
</html>
