<?php

namespace App\Http\Controllers\Shop\Payments;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Helpers\Cart;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Redirect to Stripe payment gateway.
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectRe
     */
    public function pay(Order $order)
    {
        if ($order->user->id !== auth()->user()->id) {
            abort(404);
        }

        $items = [];
        foreach ($order->orderProducts as $orderProduct) {
            $items[] = [
                'price_data' => [
                    'currency' => getenv('CURRENCY'),
                    'unit_amount' => $orderProduct->price * 100,
                    'product_data' => [
                        'name' => $orderProduct->product->name,
                        'images' => [
                            $orderProduct->product->getCoverUrl('large')
                        ],
                    ],
                ],
                'quantity' => $orderProduct->units,
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $items,
            'mode' => 'payment',
            'success_url' => route('payments.stripe.success'),
            'cancel_url' => route('checkout'),
        ]);

        session()->put('session', $session->id);
        $order->payment_ref = $session->payment_intent;
        $order->save();

        return view('shop.payments.stripe')->with('sessionId', $session->id);
    }

    /**
     * Check if the payment is completed.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success()
    {
        $checkoutSessionId = session()->get('checkoutSession');
        $session = Session::retrieve($checkoutSessionId);

        $order = Order::where('payment_ref', $session->payment_intent)->firstOrFail();
        if ($order->isPaid()) {
            return redirect()->route('orders.paid', ['order' => $order]);
        }

        if ($session->payment_status !== 'paid') {
            abort(401);
        }

        $order->setAsPaid();
        Cart::empty();

        return redirect()->route('orders.paid', ['order' => $order]);
    }
}
