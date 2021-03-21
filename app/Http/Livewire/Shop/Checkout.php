<?php

namespace App\Http\Livewire\Shop;

use App\Helpers\Cart;
use App\Models\PaymentMethod;
use Livewire\Component;
use PragmaRX\Countries\Package\Countries;
use App\Models\User;
use App\Models\Profile;
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

    protected $rules = [
        'user.name' => 'required|string',
        'profile.lastname' => 'required|string',
        'profile.country' => 'required|string',
        'profile.region' => 'required|string',
        'profile.city' => 'required|string',
        'profile.address' => 'required|string',
        'profile.zip' => 'required',
        'profile.phone' => 'required',
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

    public function saveAddress()
    {
        $this->validate();

        $this->user->save();
        $this->profile->save();
    }

    public function calculateTotalPrice()
    {
        $this->price = Cart::getTotalPrice();
        $shippingCarrier = ShippingCarrier::find($this->shippingCarrierId);
        if ($shippingCarrier) {
            $this->price += $shippingCarrier->total_price;
        }
        $paymentMethod = PaymentMethod::find($this->paymentMethodId);
        if ($paymentMethod) {
            $this->price += $paymentMethod->total_price;
        }
    }
}
