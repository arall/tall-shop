<?php

namespace App\Http\Livewire\Shop;

use App\Helpers\Cart as Helper;
use Livewire\Component;

class CartIcon extends Component
{
    public $cartTotal = 0;

    protected $listeners = [
        'cartUpdated' => 'updateCartTotal',
    ];

    public function mount(): void
    {
        $this->updateCartTotal();
    }

    public function render()
    {
        return view('livewire.shop.cart-icon');
    }

    public function updateCartTotal(): void
    {
        $this->cartTotal = Helper::getTotalProducts();
    }
}
