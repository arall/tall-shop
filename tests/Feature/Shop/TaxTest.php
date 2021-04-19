<?php

namespace Tests\Feature\Shop;

use App\Helpers\Location;
use App\Models\Product;
use Database\Seeders\ProductTypeSeeder;
use Database\Seeders\ProductCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_product_tax_when_taxes_are_active()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $product = Product::factory()->create();
        $taxRatio = 0.21;

        config(['shop.taxes' => true]);
        config(['shop.product_price_contains_taxes' => true]);
        config(['shop.tax_ratio' => $taxRatio]);

        $this->get(route('product', ['product' => $product]))
            ->assertSuccessful()
            ->assertSee('Including taxes')
            ->assertSee('(' . ($taxRatio * 100) . '%)');
    }

    /** @test */
    public function can_view_product_with_taxes_non_included()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $product = Product::factory()->create();
        $taxRatio = 0.21;
        $priceWithtaxes = ($taxRatio * $product->price) + $product->price;

        config(['shop.taxes' => true]);
        config(['shop.product_price_contains_taxes' => false]);
        config(['shop.tax_ratio' => $taxRatio]);

        $this->get(route('product', ['product' => $product]))
            ->assertSuccessful()
            ->assertSee(number_format($priceWithtaxes, 2, ',', '.') . getenv('CURRENCY_SIGN'));
    }

    /** @test */
    public function cant_view_taxes_when_those_are_disabled()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $product = Product::factory()->create();

        config(['shop.taxes' => false]);

        $this->get(route('product', ['product' => $product]))
            ->assertSuccessful()
            ->assertSee($product->price)
            ->assertDontSee('taxes');
    }

    /** @test */
    public function can_view_taxes_based_on_user_location()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $product = Product::factory()->create();

        config(['shop.taxes' => true]);
        config(['shop.tax_ratio' => false]);
        config(['location.testing.ip' => '2.16.7.12']);

        $this->assertEquals('DE', Location::getCountry());
        $this->assertEquals(0.16, Location::getTaxRatio());

        $this->get(route('product', ['product' => $product]))
            ->assertSuccessful()
            ->assertSee('(16%)');
    }
}
