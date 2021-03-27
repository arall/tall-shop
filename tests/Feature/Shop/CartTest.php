<?php

namespace Tests\Feature\Shop;

use App\Models\Product;
use Livewire\Livewire;
use Database\Seeders\ProductTypeSeeder;
use Database\Seeders\ProductCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_add_product_to_cart_from_products()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $product = Product::factory()->create();

        Livewire::test('shop.products')
            ->call('addToCart', $product->id);

        $this->get(route('cart'))
            ->assertSee($product->name);
    }

    /** @test */
    public function can_add_product_to_cart_from_product()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $product = Product::factory()->create();

        Livewire::test('shop.product', ['product' => $product])
            ->call('addToCart', $product->id);

        $this->get(route('cart'))
            ->assertSee($product->name);
    }
}
