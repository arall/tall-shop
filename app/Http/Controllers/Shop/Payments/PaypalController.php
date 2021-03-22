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
            'currency' => 'EUR',
            'issuer' => config('app.name'),
            'description' => 'test',
            'returnUrl' => route('payments.paypal.success'),
            'cancelUrl' => route('checkout'),
        ];

        $response = $this->gateway->purchase($params)->send();

        if ($response->isRedirect()) {
            $order->payment_ref = $response->getTransactionReference();
            $order->save();

            return $response->getRedirectResponse();
        } else {
            abort(500, $response->getMessage());
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
        $order = Order::where('payment_ref', $request->input('paymentId'))->firstOrFail();

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
