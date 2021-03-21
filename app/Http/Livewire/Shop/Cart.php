<?php

namespace App\Http\Livewire\Shop;

use App\Helpers\Cart as Helper;
use Livewire\Component;

class Cart extends Component
{
    public $cart;
    public $totalPrice;
    public $totalProducts;

    protected $listeners = [
        'cartUpdated' => 'render',
    ];

    public function render()
    {
        $this->cart = Helper::get();
        $this->totalPrice = Helper::getTotalPrice();
        $this->totalProducts = Helper::getTotalProducts();

        return view('livewire.shop.cart');
    }

    /**
     * Increase the quantity of a product.
     *
     * @param string $hash
     * @return void
     */
    public function increase(string $hash)
    {
        Helper::increase($hash);
        $this->emit('cartUpdated');
    }

    /**
     * Decrease the quantity of a product.
     *
     * Triggers the cartUpdated event, as the product
     * might be removed entirely if the quantity is below 1.
     *
     * @param string $hash
     * @return void
     */
    public function decrease(string $hash)
    {
        Helper::decrease($hash);
        $this->emit('cartUpdated');
    }

    /**
     * Remove a product totally.
     *
     * @param string $hash
     * @return void
     */
    public function remove(string $hash)
    {
        Helper::remove($hash);
        $this->emit('cartUpdated');
    }

    /**
     * Empty the cart.
     *
     * @return void
     */
    public function empty()
    {
        Helper::empty();
        $this->emit('cartUpdated');
    }
}
