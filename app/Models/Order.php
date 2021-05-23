<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Taxes;

class Order extends Model
{
    use HasFactory;

    /**
     * Statuses
     *
     * @var array
     */
    const STATUSES = [
        0 => 'Pending Payment',
        1 => 'Payment Completed',
        2 => 'In preparation',
        3 => 'Shipped',
        4 => 'Received',
        6 => 'On hold',
        7 => 'Canceled',
    ];

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
     * Paid status ID.
     *
     * @var int
     */
    const STATUS_PAID = 1;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payment_date' => 'datetime',
    ];

    /**
     * Get status as string.
     *
     * @return string
     */
    public function getStatus()
    {
        return self::STATUSES[$this->status];
    }

    /**
     * Create a new order.
     *
     * @todo Calculate shipping price based on products weight.
     * @todo Calculate payment method price based on total price.
     * @todo Calculate untexed price for "tax already included" prices.
     *
     * @return Order
     */
    static function create(User $user, UserAddress $address, UserInvoiceAddress $invoiceAddress = null, ShippingCarrier $shippingCarrier, PaymentMethod $paymentMethod, $taxRatio, array $cart)
    {
        $order = new Order;
        $order->user()->associate($user);

        $order->tax = $taxRatio;

        $order->shippingCarrier()->associate($shippingCarrier);
        $order->paymentMethod()->associate($paymentMethod);
        $order->shipping_price_untaxed = Taxes::calcPriceWithoutTax($shippingCarrier->price, $taxRatio);
        $order->payment_method_price_untaxed = Taxes::calcPriceWithoutTax($paymentMethod->price, $taxRatio);
        $order->shipping_price = Taxes::calcPriceWithTax($shippingCarrier->price, $taxRatio);
        $order->payment_method_price = Taxes::calcPriceWithTax($paymentMethod->price, $taxRatio);

        $order->firstname = $user->name;
        $order->fill($address->toArray());

        $order->products_price_untaxed = 0;
        $order->products_price = 0;

        $order->save();

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            $options = [];
            foreach ($item['option_ids'] as $optionId) {
                $option = ProductVariantOption::find($optionId);
                $options[] = $option->variant->name . ': ' . $option->name;
            }

            $productUnitPriceUntaxed = Taxes::calcPriceWithoutTax($product->getPrice($item['option_ids']), $taxRatio);
            $productTotalPriceUntaxed = $productUnitPriceUntaxed * $item['units'];
            $order->products_price_untaxed += $productTotalPriceUntaxed;

            $productUnitPrice = Taxes::calcPriceWithTax($product->getPrice($item['option_ids']), $taxRatio);
            $productTotalPrice = $productUnitPrice * $item['units'];
            $order->products_price += $productTotalPrice;

            $order->orderProducts()->create([
                'product_id' => $product->id,
                'variants' => $options,
                'units' => $item['units'],
                'unit_price' => $productUnitPriceUntaxed,
                'price' => $productTotalPriceUntaxed,
            ]);
        }

        $order->total_price_untaxed = $order->payment_method_price_untaxed + $order->shipping_price_untaxed + $order->products_price_untaxed;
        $order->total_price = $order->payment_method_price + $order->shipping_price + $order->products_price;

        $order->save();

        Invoice::create($invoiceAddress ?: $address, $order);

        return $order;
    }

    /**
     * Set the order as paid.
     */
    public function setAsPaid()
    {
        $this->status = self::STATUS_PAID;
        $this->payment_date = Carbon::now();
        $this->save();

        if ($this->invoice) {
            $this->invoice->setAsSubmitted();
        }
    }

    /**
     * Check if the order has been paid.
     *
     * @return boolean
     */
    public function isPaid()
    {
        return $this->status == self::STATUS_PAID;
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

    /**
     * Related Invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
