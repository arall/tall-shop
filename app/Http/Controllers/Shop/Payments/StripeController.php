<?php

namespace App\Http\Controllers\Shop\Payments;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class StripeController extends Controller
{
    /**
     * Omnipay gateway instance.
     */
    private $gateway;

    public function __construct()
    {
        $this->gateway = App::make('omnipay')->gateway('Stripe');
    }

    /**
     * Redirect to Stripe payment gateway.
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pay(Order $order)
    {
        if ($order->user->id !== auth()->user()->id) {
            abort(404);
        }

        print_r(session()->get('stripePaymentMethod'));
        exit;

        $params = [
            'amount' => $order->price,
            'currency' => config('omnipay.defaults.currency'),
            'description' => 'Order ' . $order->id,
            'paymentMethod' => session()->get('paymentMethod'),
            'returnUrl' => route('payments.paypal.success', ['orderId' => $order->id]),
            'cancelUrl' => route('checkout'),
            'confirm'   => true,
        ];

        $response = $this->gateway->authorize($params)->send();

        if (!$response->isSuccessful()) {
            abort(401, $response->getMessage());
        }

        $order->payment_ref = $response->getTransactionReference();
        $order->save();

        if ($response->isRedirect()) {
            return $response->getRedirectResponse();
        } else {
            die('no redirect');
            return redirect()->route('payments.paypal.success', ['orderId' => $order->id]);
        }
    }

    /**
     * Check if the payment is completed.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        $order = Order::findOrFail($request->input('orderId'));
        if ($order->isPaid()) {
            return redirect()->route('orders.paid', ['order' => $order]);
        }

        $response = $this->gateway->confirm([
            'paymentIntentReference' => $order->payment_ref,
            'returnUrl' => route('payments.paypal.success', ['orderId' => $order->id]),
        ])->send();


        if (!$response->isSuccessful()) {
            abort(401, $response->getMessage());
        }

        // 3DS auth required
        if ($response->isRedirect()) {
            return $response->redirect();
        }

        $order->setAsPaid();

        return redirect()->route('orders.paid', ['order' => $order]);
    }
}
