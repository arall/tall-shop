<?php

namespace App\Http\Livewire\Shop;

use App\Models\Product;
use App\Helpers\Cart;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    /**
     * Query limit per page.
     *
     * @var string
     */
    public $limit = 4;

    /**
     * Search query.
     *
     * @var string
     */
    public $search = '';

    /**
     * @var string
     */
    public $sortField = 'id';

    /**
     * @var string
     */
    public $sortDirection = 'asc';

    /**
     * @var string[]
     */
    public $queryString = ['search', 'sortField', 'sortDirection'];

    public function render()
    {
        $query = Product::search($this->search);

        $products = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->limit);

        return view('livewire.shop.products', [
            'products' => $products,
        ]);
    }

    /**
     * Sort columns.
     *
     * @param $field
     */
    public function sortBy($field)
    {
        if ($this->sortField == $field) {
            $this->sortDirection = $this->sortDirection ===  'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    /**
     * Add a product into the cart.
     *
     * @param integer $productId
     * @param array $variantOptions
     * @return void
     */
    public function addToCart(int $productId, array $variantOptions = [])
    {
        Cart::add($productId, $variantOptions);
        $this->emit('cartUpdated');
    }
}
