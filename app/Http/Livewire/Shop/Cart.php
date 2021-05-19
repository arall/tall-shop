<?php

namespace App\Http\Livewire\Shop;

use App\Helpers\Cart as CartHelper;
use App\Helpers\Taxes;
use Livewire\Component;

class Cart extends Component
{
    public $cart;
    public $totalPrice;
    public $totalTaxes;

    protected $listeners = [
        'cartUpdated' => 'render',
    ];

    public function render()
    {
        $this->cart = CartHelper::get();
        $this->totalPrice = CartHelper::getTotalPrice();
        $this->totalTaxes = Taxes::calcTaxPrice($this->totalPrice);

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
        CartHelper::increase($hash);
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
        CartHelper::decrease($hash);
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
        CartHelper::remove($hash);
        $this->emit('cartUpdated');
    }

    /**
     * Empty the cart.
     *
     * @return void
     */
    public function empty()
    {
        CartHelper::empty();
        $this->emit('cartUpdated');
    }
}
