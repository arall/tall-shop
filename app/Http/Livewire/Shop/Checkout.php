<?php

namespace App\Http\Livewire\Shop;

use App\Helpers\Cart;
use App\Models\PaymentMethod;
use Livewire\Component;
use PragmaRX\Countries\Package\Countries;
use App\Models\User;
use App\Models\Profile;
use App\Models\Order;
use App\Models\ShippingCarrier;

class Checkout extends Component
{
    public User $user;
    public Profile $profile;
    public $shippingCarriers;
    public $shippingCarrierId;
    public $paymentMethods;
    public $paymentMethodId;
    public $price;
    public $hasStripe = false;
    public $stripePaymentMethod;

    protected $rules = [
        'user.name' => 'required|string',
        'profile.lastname' => 'required|string',
        'profile.country' => 'required|string',
        'profile.region' => 'required|string',
        'profile.city' => 'required|string',
        'profile.address' => 'required|string',
        'profile.zip' => 'required',
        'profile.phone' => 'required',
        'shippingCarrierId' => 'required|exists:shipping_carriers,id',
        'paymentMethodId' => 'required|exists:payment_methods,id',
    ];

    public function mount()
    {
        $this->user = auth()->user();
        $profile = $this->user->profile;
        if (!$profile) {
            $profile = $this->user->profile()->create();
        }
        $this->profile = $profile;
        $this->shippingCarriers = ShippingCarrier::all();
        $this->paymentMethods = PaymentMethod::all();
        $this->calculateTotalPrice();

        if ($this->paymentMethods->contains('type', 'stripe')) {
            $this->hasStripe = true;
        }
    }

    public function render()
    {
        return view('livewire.shop.checkout')
            ->with('countries', Countries::all()->pluck('name.common', 'cca2')->toArray());
    }

    public function save()
    {
        $this->validate();

        $this->user->save();
        $this->profile->save();

        $order = $this->createOrder();

        if ($this->stripePaymentMethod) {
            session()->put('stripePaymentMethod', $this->stripePaymentMethod);
        }

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
            $this->user,
            ShippingCarrier::find($this->shippingCarrierId),
            PaymentMethod::find($this->paymentMethodId),
            Cart::get()
        );
    }

    public function calculateTotalPrice()
    {
        $this->price = Cart::getTotalPrice();
        if ($this->shippingCarrierId) {
            $shippingCarrier = ShippingCarrier::find($this->shippingCarrierId);
            if ($shippingCarrier) {
                $this->price += $shippingCarrier->price;
            }
        }
        if ($this->paymentMethodId) {
            $paymentMethod = PaymentMethod::find($this->paymentMethodId);
            if ($paymentMethod) {
                $this->price += $paymentMethod->price;
            }
        }
    }
}
