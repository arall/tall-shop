<?php

namespace App\Http\Controllers\Shop\Payments;

use App\Helpers\Cart;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaypalController extends Controller
{
    /**
     * Paypal client instance.
     */
    private $client;

    public function __construct()
    {
        $environment = new SandboxEnvironment(
            config('services.paypal.client'),
            config('services.paypal.secret')
        );
        $this->client = new PayPalHttpClient($environment);
    }

    /**
     * Redirect to Paypal payment gateway.
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pay(Order $order)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');

        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $order->id,
                'amount' => [
                    'currency_code' => getenv('CURRENCY'),
                    'value' => $order->total_price,
                ],
            ]],
            'application_context' => [
                'return_url' => route('payments.paypal.success'),
                'cancel_url' => route('checkout'),
            ]
        ];

        try {
            $response = $this->client->execute($request);
        } catch (\Exception $e) {
            dd($e->getMessage());
            abort(500, $e->getMessage());
        }

        $order->payment_ref = $response->result->id;
        $order->save();

        $link = array_values(array_filter(
            $response->result->links,
            function ($e) {
                return $e->rel == "approve";
            }
        ))[0]->href;

        return redirect()->to($link);
    }

    /**
     * Check if the payment is completed.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        $order = Order::where('payment_ref', $request->input('token'))->firstOrFail();
        if ($order->isPaid()) {
            return redirect()->route('orders.paid', ['order' => $order]);
        }

        $request = new OrdersCaptureRequest($request->input('token'));
        $request->prefer('return=representation');
        try {
            $response = $this->client->execute($request);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }

        if ($response->result->status !== 'COMPLETED') {
            abort(401);
        }

        $order->setAsPaid();
        Cart::empty();

        return redirect()->route('orders.paid', ['order' => $order]);
    }
}
