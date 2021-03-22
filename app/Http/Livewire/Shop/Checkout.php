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
    public $shippingCarrier;
    public $paymentMethods;
    public $paymentMethod;
    public $price;

    protected $rules = [
        'user.name' => 'required|string',
        'profile.lastname' => 'required|string',
        'profile.country' => 'required|string',
        'profile.region' => 'required|string',
        'profile.city' => 'required|string',
        'profile.address' => 'required|string',
        'profile.zip' => 'required',
        'profile.phone' => 'required',
        'shippingCarrier' => 'required',
        'paymentMethod' => 'required',
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
            ShippingCarrier::find($this->shippingCarrier),
            PaymentMethod::find($this->paymentMethod),
            Cart::get()
        );
    }

    public function calculateTotalPrice()
    {
        $this->price = Cart::getTotalPrice();
        if ($this->shippingCarrier) {
            $shippingCarrier = ShippingCarrier::find($this->shippingCarrier);
            if ($shippingCarrier) {
                $this->price += $shippingCarrier->price;
            }
        }
        if ($this->paymentMethod) {
            $paymentMethod = PaymentMethod::find($this->paymentMethod);
            if ($paymentMethod) {
                $this->price += $paymentMethod->price;
            }
        }
    }
}
