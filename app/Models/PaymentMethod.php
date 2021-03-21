<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($paymentMethod) {
            $paymentMethod->total_price = $paymentMethod->price + (($paymentMethod->price * $paymentMethod->tax) / 100);
        });
    }
}
