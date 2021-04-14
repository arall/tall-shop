<?php

namespace App\Http\Livewire\Shop;

use App\Helpers\Cart as CartHelper;
use App\Helpers\Taxes as TaxesHelper;
use App\Models\PaymentMethod;
use Livewire\Component;
use PragmaRX\Countries\Package\Countries;
use App\Models\Order;
use App\Models\ShippingCarrier;
use App\Models\UserAddress;

class Checkout extends Component
{
    public $addressId;
    public UserAddress $address;
    public $shippingCarrierId;
    public $paymentMethodId;
    public $price;
    public $taxes;

    protected $rules = [
        'address.firstname' => 'required|string',
        'address.lastname' => 'required|string',
        'address.country' => 'required|string',
        'address.region' => 'required|string',
        'address.city' => 'required|string',
        'address.address' => 'required|string',
        'address.zip' => 'required',
        'address.phone' => 'required',
        'shippingCarrierId' => 'required|exists:shipping_carriers,id',
        'paymentMethodId' => 'required|exists:payment_methods,id',
    ];

    public function mount()
    {
        $user = auth()->user();

        $this->address = new UserAddress;

        if ($user->addresses()->count()) {
            $this->address = $user->addresses()->orderBy('favorite', 'DESC')->first();
        }
    }

    public function render()
    {
        $user = auth()->user();

        if ($this->addressId == -1) {
            $this->address = new UserAddress;
            $this->addressId = null;
        } elseif ($this->addressId) {
            $this->address = $user->addresses()->where('id', $this->addressId)->first();
        }

        $this->calculateTotalPrice();

        return view('livewire.shop.checkout')
            ->with('addresses', $user->addresses)
            ->with('countries', Countries::all()->pluck('name.common', 'cca2')->toArray())
            ->with('shippingCarriers', ShippingCarrier::enabled()->get())
            ->with('paymentMethods', PaymentMethod::enabled()->get());
    }

    public function save()
    {
        $this->validate();

        if (!$this->addressId || $this->addressId == -1) {
            auth()->user()->addresses()->save($this->address);
        } else {
            $this->address->save();
        }

        $order = $this->createOrder();

        redirect()->route('orders.pay', ['order' => $order]);
    }

    /**
     * Create an order.
     *
     * @return Order
     */
    private function createOrder()
    {
        return Order::create(
            auth()->user(),
            $this->address,
            ShippingCarrier::find($this->shippingCarrierId),
            PaymentMethod::find($this->paymentMethodId),
            CartHelper::get()
        );
    }

    public function calculateTotalPrice()
    {
        $this->taxes = CartHelper::getTotalTaxes();
        $this->price = CartHelper::getTotalPrice();

        if ($this->shippingCarrierId) {
            $shippingCarrier = ShippingCarrier::find($this->shippingCarrierId);
            if ($shippingCarrier) {
                $price = $shippingCarrier->price;
                $tax = $shippingCarrier->price * TaxesHelper::getTaxRatio();
                if (!TaxesHelper::productPricesContainTaxes()) {
                    $price += $tax;
                }
                $this->price += $price;
                $this->taxes += $tax;
            }
        }

        if ($this->paymentMethodId) {
            $paymentMethod = PaymentMethod::find($this->paymentMethodId);
            if ($paymentMethod) {
                $price = $paymentMethod->price;
                $tax = $paymentMethod->price * TaxesHelper::getTaxRatio();
                if (!TaxesHelper::productPricesContainTaxes()) {
                    $price += $tax;
                }
                $this->price += $price;
                $this->taxes += $tax;
            }
        }
    }
}
