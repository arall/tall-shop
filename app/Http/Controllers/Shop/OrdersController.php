<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Helpers\Cart;

class OrdersController extends Controller
{
    /**
     * Generic payment gateway.
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pay(Order $order)
    {
        if ($order->user->id !== auth()->user()->id) {
            abort(404);
        }

        if ($order->paymentMethod->type == 'paypal') {
            return redirect()->route('payments.paypal.pay', ['order' => $order]);
        } elseif ($order->paymentMethod->type == 'stripe') {
            return redirect()->route('payments.stripe.pay', ['order' => $order]);
        }
    }

    /**
     * Shows that the order has been paid.
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function paid(Order $order)
    {
        if ($order->user->id !== auth()->user()->id) {
            abort(404);
        }

        if (!$order->isPaid()) {
            return redirect()->route('checkout');
        }

        Cart::empty();

        return route('order', ['order' => $order]);
    }
}
