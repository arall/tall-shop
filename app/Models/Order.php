<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipping_price', 'payment_method_price',
        'lastname', 'country', 'address', 'zip',
        'city', 'region', 'phone'
    ];


    /**
     * Create a new order.
     *
     * @return Order
     */
    static function create(User $user, ShippingCarrier $shippingCarrier, PaymentMethod $paymentMethod, array $cart)
    {
        $order = new Order;
        $order->user()->associate($user);
        $order->shippingCarrier()->associate($shippingCarrier);
        $order->paymentMethod()->associate($paymentMethod);
        $order->shipping_price = $shippingCarrier->price; // @todo calculate based on products weight
        $order->payment_method_price = $paymentMethod->price; // @todo calculate based on products price
        $order->firstname = $user->name;
        $order->fill($user->profile->toArray());
        $order->price = 0;
        $order->save();

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            $options = [];
            foreach ($item['option_ids'] as $optionId) {
                $option = ProductVariantOption::find($optionId);
                $options[] = $option->variant->name . ': ' . $option->name;
            }

            $order->price += $product->getPrice($item['option_ids']);

            $order->orderProducts()->create([
                'product_id' => $product->id,
                'variants' => $options,
                'units' => $item['units'],
                'unit_price' => $product->getPrice($item['option_ids']),
                'price' => $product->getPrice($item['option_ids']) * $item['units'],
            ]);
        }

        $order->save();

        return $order;
    }

    /**
     * Get the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Related Order Products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    /**
     * Get the shipping carrier.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingCarrier()
    {
        return $this->belongsTo(ShippingCarrier::class);
    }

    /**
     * Get the payment method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}