<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('merges guest cart into new user cart when login', function () {
    // Create product
    $product1 = new Product;
    $product1->name = 'Product 1';
    $product1->price = 10;
    $product1->description = 'Test Desc';
    $product1->save();

    // Create guest cart
    $guestOrder = new Order;
    $guestOrder->status = OrderStatus::InCart;
    $guestOrder->total_amount = 0;
    $guestOrder->save();
    
    $guestOrder->products()->attach($product1->id, ['amount' => 2]);
    $guestOrder->updateTotalAmount();
    
    // Put guest order ID in session
    Session::put('orderId', $guestOrder->id);    
    expect(Session::get('orderId'))->toBe($guestOrder->id);
    
    $user = User::factory()->create();

    // Trigger Login event manually
    Illuminate\Support\Facades\Auth::login($user);

    $user->refresh();

    // Assert session is cleared
    expect(Session::has('orderId'))->toBeFalse();

    // Assert guest order is deleted
    expect(Order::find($guestOrder->id))->toBeNull();

    // Assert user has a current order
    expect($user->current_order_id)->not->toBeNull();
    
    $userOrder = $user->currentOrder;
    expect($userOrder->products->count())->toBe(1);
    expect($userOrder->products->first()->id)->toBe($product1->id);
    expect($userOrder->products->first()->pivot->amount)->toBe(2);
    expect((int)$userOrder->total_amount)->toBe(20);
});

test('merges guest cart into existing user cart on login and sums amounts for same product', function () {
    // Create product
    $product1 = new Product;
    $product1->name = 'Product 1';
    $product1->price = 10.0;
    $product1->description = 'Test Desc';
    $product1->save();
    
    $product2 = new Product;
    $product2->name = 'Product 2';
    $product2->price = 20.0;
    $product2->description = 'Test Desc';
    $product2->save();

    // Create guest cart
    $guestOrder = new Order;
    $guestOrder->status = OrderStatus::InCart;
    $guestOrder->total_amount = 0;
    $guestOrder->save();
    
    $guestOrder->products()->attach($product1->id, ['amount' => 2]);
    $guestOrder->products()->attach($product2->id, ['amount' => 1]);
    $guestOrder->updateTotalAmount();
    
    Session::put('orderId', $guestOrder->id);

    // Create a user with existing cart
    $user = User::factory()->create();
    
    $userOrder = new Order;
    $userOrder->status = OrderStatus::InCart;
    $userOrder->total_amount = 0;
    $userOrder->user_id = $user->id;
    $userOrder->save();
    
    $user->current_order_id = $userOrder->id;
    $user->save();
    
    // User already has product1 in cart
    $userOrder->products()->attach($product1->id, ['amount' => 3]);
    $userOrder->updateTotalAmount();
    Illuminate\Support\Facades\Auth::login($user);

    $user->refresh();
    $finalOrder = $user->currentOrder;
    expect(Session::has('orderId'))->toBeFalse();
    expect(Order::find($guestOrder->id))->toBeNull();
    expect($finalOrder->id)->toBe($userOrder->id);
    expect($user->current_order_id)->toBe($userOrder->id);

    expect($finalOrder->products->count())->toBe(2);

    $pivot1 = $finalOrder->products()->where('product_id', $product1->id)->first()->pivot;
    expect($pivot1->amount)->toBe(5);
    
    $pivot2 = $finalOrder->products()->where('product_id', $product2->id)->first()->pivot;
    expect($pivot2->amount)->toBe(1);
    
    // Total should 70
    expect((int)$finalOrder->total_amount)->toBe(70);
});