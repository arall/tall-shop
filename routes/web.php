<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Passwords\Confirm;
use App\Http\Livewire\Auth\Passwords\Email;
use App\Http\Livewire\Auth\Passwords\Reset;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\Verify;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\Payments\PaypalController;
use App\Http\Controllers\Shop\Payments\StripeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/**
 * Auth
 */
Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)
        ->name('login');

    Route::get('register', Register::class)
        ->name('register');
});

Route::get('password/reset', Email::class)
    ->name('password.request');

Route::get('password/reset/{token}', Reset::class)
    ->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('email/verify', Verify::class)
        ->middleware('throttle:6,1')
        ->name('verification.notice');

    Route::get('password/confirm', Confirm::class)
        ->name('password.confirm');
});

Route::middleware('auth')->group(function () {
    Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('logout', LogoutController::class)
        ->name('logout');
});

/**
 * Shop
 */
Route::get('/products', App\Http\Livewire\Shop\Products::class)->name('products');
Route::get('/product/{product}', App\Http\Livewire\Shop\Product::class)->name('product');
Route::get('/shopping-cart', App\Http\Livewire\Shop\Cart::class)->name('cart');
Route::middleware('auth')->group(function () {
    Route::get('/checkout', App\Http\Livewire\Shop\Checkout::class)->name('checkout');
    Route::get('/orders/pay/{order}', [App\Http\Controllers\Shop\OrdersController::class, 'pay'])->name('orders.pay');
    Route::get('/orders/paid/{order}', [App\Http\Controllers\Shop\OrdersController::class, 'paid'])->name('orders.paid');

    /**
     * Payment gateways
     */
    Route::prefix('payments/paypal')->name('payments.paypal.')->group(function () {
        Route::get('/pay/{order}', [PaypalController::class, 'pay'])->name('pay');
        Route::get('/success', [PaypalController::class, 'success'])->name('success');
    });
    Route::prefix('payments/stripe')->name('payments.stripe.')->group(function () {
        Route::get('/pay/{order}', [StripeController::class, 'pay'])->name('pay');
        Route::get('/success', [StripeController::class, 'success'])->name('success');
    });
});
