<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * Statuses
     *
     * @var array
     */
    const STATUSES = [
        0 => 'Draft',
        1 => 'Submitted',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Submitted status ID.
     *
     * @var int
     */
    const STATUS_SUBMITTED = 1;

    /**
     * Create an invoice.
     *
     * @param UserInvoiceAddress|UserAddress $address
     * @param Order $order
     * @return Invoice
     */
    public static function create($address, Order $order)
    {
        $invoice = new Invoice();

        $invoice->user()->associate($order->user);
        $invoice->order()->associate($order);

        $invoice->vat = $address->vat ?: null;
        $invoice->name = $address->name ?: $address->firstname;
        $invoice->country = $address->country;
        $invoice->region = $address->region;
        $invoice->address = $address->address;
        $invoice->city = $address->city;
        $invoice->zip = $address->zip;
        $invoice->phone = $address->phone;

        $invoice->tax = $order->tax;
        $invoice->price_untaxed = $order->total_price_untaxed;
        $invoice->price = $order->total_price;

        $invoice->save();

        $invoice->invoiceProducts()->create([
            'product_name' => __('Shipping'),
            'units' => 1,
            'variants' => [$order->shippingCarrier->name],
            'unit_price' => $order->shipping_price_untaxed,
            'price' => $order->shipping_price_untaxed,
        ]);

        $invoice->invoiceProducts()->create([
            'product_name' => __('Payment Method'),
            'units' => 1,
            'variants' => [$order->paymentMethod->name],
            'unit_price' => $order->payment_method_price_untaxed,
            'price' => $order->payment_method_price_untaxed,
        ]);

        foreach ($order->orderProducts as $orderProduct) {
            $invoice->invoiceProducts()->create([
                'product_name' => __('Shipping'),
                'product_id' => $orderProduct->product_id,
                'variants' => $orderProduct->variants,
                'units' => $orderProduct->units,
                'unit_price' => $orderProduct->unit_price,
                'price' => $orderProduct->price,
            ]);
        }

        return $invoice;
    }

    /**
     * Check if the invoice has been submitted.
     *
     * @return boolean
     */
    public function isSubmitted()
    {
        return $this->status == self::STATUS_SUBMITTED;
    }

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
     * Set the invoice as submitted.
     */
    public function setAsSubmitted()
    {
        $this->status = self::STATUS_SUBMITTED;
        $this->date = Carbon::now();
        $this->number = $this->getNextNumber();
        $this->save();
    }

    /**
     * Get the next invoice number.
     *
     * @return string
     */
    public function getNextNumber()
    {
        $last = self::orderBy('number', 'desc')->value('number');

        return str_pad($last + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
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
     * Related Invoice Products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
