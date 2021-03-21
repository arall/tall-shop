<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCarrier extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($shippingCarrier) {
            $shippingCarrier->total_price = $shippingCarrier->price + (($shippingCarrier->price * $shippingCarrier->tax) / 100);
            $shippingCarrier->total_price_kg = $shippingCarrier->price_kg + (($shippingCarrier->price_kg * $shippingCarrier->tax) / 100);
        });
    }
}
