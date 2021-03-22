<?php

namespace App\Http\Controllers\Shop\Payments;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaypalController extends Controller
{
    /**
     * Omnipay gateway instance.
     */
    private $gateway;

    public function __construct()
    {
        $this->gateway = \Omnipay::gateway('PayPal_Rest');
    }

    /**
     * Redirect to Paypal payment gateway.
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pay(Order $order)
    {
        if ($order->user->id !== auth()->user()->id) {
            abort(404);
        }

        $params = [
            'amount' => $order->price,
            'currency' => config('omnipay.defaults.currency'),
            'issuer' => config('app.name'),
            'description' => 'Order ' . $order->id,
            'returnUrl' => route('payments.paypal.success'),
            'cancelUrl' => route('checkout'),
        ];

        $response = $this->gateway->purchase($params)->send();

        if (!$response->isRedirect()) {
            abort(500, $response->getMessage());
        }

        $order->payment_ref = $response->getTransactionReference();
        $order->save();

        return $response->getRedirectResponse();
    }

    /**
     * Check if the payment is completed.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        $order = Order::where('payment_ref', $request->input('paymentId'))->firstOrFail();
        if ($order->isPaid()) {
            return redirect()->route('orders.paid', ['order' => $order]);
        }

        $transaction = $this->gateway->completePurchase([
            'payer_id'             => $request->input('PayerID'),
            'transactionReference' => $request->input('paymentId'),
        ]);
        $response = $transaction->send();
        if (!$response->isSuccessful()) {
            abort(401, $response->getMessage());
        }

        $order->setAsPaid();

        return redirect()->route('orders.paid', ['order' => $order]);
    }
}
