<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;
use App\Helpers\Cart;
use Illuminate\Support\Arr;

class Product extends Component
{
    public $product;
    public $price;
    public $optionIds = [];

    public function mount(\App\Models\Product $product)
    {
        $this->product = $product;

        foreach ($product->groupedOptions() as $variantId => $options) {
            $this->optionIds[$variantId] = Arr::first($options)->id;
        }

        $this->calculatePrice();
    }

    public function render()
    {
        return view('livewire.shop.product');
    }

    /**
     * Change Product options.
     *
     * @return void
     */
    public function changeOptions()
    {
        $this->calculatePrice();
    }

    /**
     * Add a product into the cart.
     *
     * @return void
     */
    public function addToCart()
    {
        Cart::add($this->product->id, $this->optionIds);
        $this->emit('cartUpdated');
    }

    /**
     * Calculate the total price of the product.
     */
    private function calculatePrice()
    {
        $this->price = $this->product->getPrice($this->optionIds);
    }
}
