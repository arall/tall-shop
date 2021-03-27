<?php

namespace Tests\Feature\Shop;

use App\Models\Product;
use Livewire\Livewire;
use Database\Seeders\ProductTypeSeeder;
use Database\Seeders\ProductCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_products()
    {
        $this->get(route('products'))
            ->assertSuccessful()
            ->assertSeeLivewire('shop.products');
    }

    /** @test */
    public function can_view_product()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $product = Product::factory()->create();

        $this->get(route('product', ['product' => $product]))
            ->assertSuccessful()
            ->assertSeeLivewire('shop.product');
    }

    /** @test */
    public function can_add_product_to_cart_from_products()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $product = Product::factory()->create();

        Livewire::test('shop.products')
            ->call('addToCart', $product->id);

        $this->get(route('checkout'))
            ->assertSee($product->name);
    }
}
