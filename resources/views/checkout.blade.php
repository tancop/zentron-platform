<!doctype html>
<html lang="en">
<head>
    <x-meta-tags :title="$isReview ? 'order review' : 'checkout'"/>
    @vite('resources/css/style.css')
    @vite('resources/css/form.css')
    @vite('resources/css/checkout.css')
</head>

<body class="checkout-page">

@include('components.header')

<x-checkout-stepper :step="($isReview ? 'review' : 'details')"/>

<main class="checkout-main">
    @if (auth()->guest())
        <section
            class="guest-signin-card"
            aria-label="Guest checkout sign-in option"
        >
            <p>
                Checking out as guest. Want to keep this order in your
                account?
            </p>
            <div class="checkout-link guest-signin-link">
                <a href="/login">Sign in and continue checkout</a>
            </div>
        </section>
    @endif

    <form class="checkout-layout" method="post"
          action="{{$isReview ? '/checkout/confirm' : '/checkout/setDetails'}}">
        @php
            $selectedDeliveryMethod = old('delivery-method', $order->delivery_type_id ?? ($deliveryMethods->first()?->id));
            $selectedCountry = old('country', $order->country ?: 'SK');
        @endphp
        <section class="checkout-form-box" aria-label="Customer form">
            <fieldset {{ $isReview ? 'disabled' : '' }}>
                <h2>Customer details</h2>

                <div class="checkout-form">
                    <label for="name">Contact name</label>
                    <input
                        id="name"
                        name="name"
                        value="{{ $order->contact_name }}"
                        type="text"
                        autocomplete="name"
                        placeholder="Peter Kováčik"
                    />

                    <label for="address-1">Address 1</label>
                    <input
                        id="address-1"
                        name="address-1"
                        value="{{ $order->address_1 }}"
                        type="text"
                        autocomplete="address-line1"
                        placeholder="Bratislavská 10"
                    />

                    <label for="address-2">Address 2</label>
                    <input
                        id="address-2"
                        name="address-2"
                        value="{{ $order->address_2 }}"
                        type="text"
                        autocomplete="address-line2"
                        placeholder="Apartment / floor (optional)"
                    />

                    <div class="field-row field-row-zip-city">
                        <div>
                            <label for="zip">ZIP code</label>
                            <input
                                id="zip"
                                name="zip"
                                value="{{ $order->zip == '00000' ? '' : $order->zip }}"
                                type="text"
                                autocomplete="postal-code"
                                placeholder="04011"
                            />
                        </div>
                        <div>
                            <label for="city">City</label>
                            <input
                                id="city"
                                name="city"
                                value="{{ $order->city }}"
                                type="text"
                                autocomplete="address-level1"
                                placeholder="Košice"
                            />
                        </div>
                    </div>

                    <label for="country">Country</label>
                    <select id="country" name="country">
                        <option value="SK" @selected($selectedCountry === 'SK')>Slovakia</option>
                        <option value="CZ" @selected($selectedCountry === 'CZ')>Czech Republic</option>
                        <option value="AT" @selected($selectedCountry === 'AT')>Austria</option>
                    </select>

                    <div class="field-row">
                        <label for="phone-number">Phone number</label>
                        <input
                            id="phone-number"
                            name="phone-number"
                            value="{{ $order->contact_phone }}"
                            type="tel"
                            placeholder="+421 900 000 000"
                        />
                    </div>

                    <label for="email">Email</label>
                    <input
                        id="email"
                        name="email"
                        value="{{ $order->contact_email }}"
                        type="email"
                        autocomplete="email"
                        placeholder="name@email.com"
                    />

                    <fieldset class="delivery-methods">
                        <legend>Delivery</legend>

                        @foreach ($deliveryMethods as $option)
                            <label class="delivery-option">
                                <input
                                    type="radio"
                                    name="delivery-method"
                                    value="{{ $option->id }}"
                                    @checked((string) $selectedDeliveryMethod === (string) $option->id)
                                />
                                <span>{{ $option->name }}</span>
                                <span>{{ $option->price }} €</span>
                            </label>
                        @endforeach
                    </fieldset>

                    <div class="checkbox-row">
                        <div>
                            <input id="terms" type="checkbox" checked/>
                            <label for="terms">Terms of Service</label>
                        </div>
                        <div>
                            <input id="subscribe" type="checkbox" checked/>
                            <label for="subscribe">
                                Subscribe to newsletter
                            </label>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="form-errors">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </fieldset>
        </section>

        <x-checkout-summary :order="$order"
                            :forward-text="$isReview ? 'Go to payment' : 'Go to review'"
                            :back-link="$isReview ? '/checkout' : '/cart'"/>
    </form>
</main>

@include('components.footer')

@include('components.mobile-nav')

</body>
</html>
