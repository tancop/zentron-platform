<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::with('categories')->limit(10)->get();

    $promoDealId = Product::where('name', 'Retro Lava Lamp Set')->value('id');
    $promoDiscountId = Product::where('name', 'Gaming PC Build - Storm')->value('id');

    // Fallback
    $randomIds = Product::inRandomOrder()->limit(2)->pluck('id');
    
    return view('index', [
        'products' => $products,
        'random_product_id_deals' => $promoDealId ?? ($randomIds[0] ?? null),
        'random_product_id_discounts' => $promoDiscountId ?? ($randomIds[1] ?? null)
    ]);
});

Route::get('/product/new', [ProductController::class, 'new'])
    ->can('create', Product::class)
    ->name('product.new');

Route::post('/product/create', [ProductController::class, 'create'])
    ->name('product.create');

Route::get('/product/{product}/edit', [ProductController::class, 'edit'])
    ->can('update', 'product')
    ->name('product.edit');

Route::post('/product/{product}/update', [ProductController::class, 'update'])
    ->name('product.update');

Route::post('/product/{product}/delete', [ProductController::class, 'delete'])
    ->name('product.delete');

Route::post('/product/{product}/removeImage', [ProductController::class, 'removeImage'])
    ->can('update', 'product')
    ->name('product.removeImage');

Route::get('/product/{id}', [ProductController::class, 'show'])
    ->name('product');
Route::get('/products', [ProductController::class, 'all']);

Route::get('/category/new', [CategoryController::class, 'new'])
    ->can('create', Category::class)
    ->name('category.new');

Route::post('/category/create', [CategoryController::class, 'create'])
    ->name('category.create');

Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])
    ->can('update', 'category')
    ->name('category.edit');

Route::post('/category/{category}/update', [CategoryController::class, 'update'])
    ->name('category.update');

Route::get('/category/{id}', [CategoryController::class, 'show'])
    ->name('category.show');
Route::get('/categories', [CategoryController::class, 'all']);


Route::get('/brand/new', [BrandController::class, 'new'])
    ->can('create', Brand::class)
    ->name('brand.new');

Route::post('/brand/create', [BrandController::class, 'create'])
    ->name('brand.create');

Route::get('/brand/{brand}/edit', [BrandController::class, 'edit'])
    ->can('update', 'brand')
    ->name('brand.edit');

Route::post('/brand/{brand}/update', [BrandController::class, 'update'])
    ->name('brand.update');

Route::get('/brand/{id}/{page?}', [BrandController::class, 'show'])
    ->name('brand.show');
Route::get('/brands', [BrandController::class, 'all']);

Route::get('/cart', [CartController::class, 'show'])->name('cart');
Route::post('/cart/setAmount', [CartController::class, 'setAmount']);
Route::post('/cart/remove', [CartController::class, 'remove']);

Route::get('/account', [UserController::class, 'account'])->middleware('auth');

Route::get('/admin/login', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.home');
    }
    return view('admin.login');
});
Route::get('/admin/home', function () {
    $user = auth()->user();
    return view('admin.home', compact('user'));
})->can('create', Product::class)->name('admin.home');

Route::get('/admin/products', [ProductController::class, 'adminIndex'])
    ->can('create', Product::class)
    ->name('admin.products');

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout/setDetails', [CheckoutController::class, 'setDetails'])
    ->name('checkout.setDetails');

Route::get('/checkout/review', [CheckoutController::class, 'review'])
    ->name('checkout.review');
Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])
    ->name('checkout.confirm');

Route::get('/checkout/payment', [CheckoutController::class, 'payment'])
    ->name('checkout.payment');
Route::post('/checkout/acceptPayment', [CheckoutController::class, 'acceptPayment'])
    ->name('checkout.acceptPayment');

Route::get('/checkout/complete', [CheckoutController::class, 'complete'])
    ->name('checkout.complete');

Route::get('/order/{order}', [OrderController::class, 'show'])
    ->can('view', 'order')->name('order');
Route::get('/orders', [OrderController::class, 'all'])
    ->name('orders');
