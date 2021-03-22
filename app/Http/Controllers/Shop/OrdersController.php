<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrdersController extends Controller
{
    public function pay(Order $order)
    {
        if ($order->user->id !== auth()->user()->id) {
            abort(404);
        }

        print_r($order);
    }
}
