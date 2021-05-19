<?php

namespace Tests\Feature\Shop;

use App\Http\Livewire\Shop\Checkout;
use App\Http\Livewire\Shop\Product as ShopProduct;
use App\Models\PaymentMethod;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\ProductTypeSeeder;
use Database\Seeders\ProductCategorySeeder;
use App\Models\Product;
use App\Models\ShippingCarrier;
use App\Models\User;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_is_required_for_checkout()
    {
        $this->get(route('checkout'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_see_cart_products_price_in_checkout()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $this->actingAs(User::factory()->create());

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        Livewire::test(ShopProduct::class, ['product' => $product1])
            ->call('addToCart', $product1->id);

        Livewire::test(ShopProduct::class, ['product' => $product2])
            ->call('addToCart', $product2->id);

        $total = $product1->price + $product2->price;

        $this->get(route('checkout'))
            ->assertSee(
                number_format($total, 2, ',', '.') . getenv('CURRENCY_SIGN')
            );
    }

    /** @test */
    public function can_see_enabled_payment_methods()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $this->actingAs(User::factory()->create());

        $paymentMethod = PaymentMethod::factory()->create();
        $paymentMethod->status = 1;
        $paymentMethod->save();

        $product = Product::factory()->create();
        Livewire::test(ShopProduct::class, ['product' => $product])
            ->call('addToCart', $product->id);

        $this->get(route('checkout'))
            ->assertSee($paymentMethod->name);
    }

    /** @test */
    public function can_see_enabled_shipping_carriers()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $this->actingAs(User::factory()->create());

        $shippingCarrier = ShippingCarrier::factory()->create();
        $shippingCarrier->status = 1;
        $shippingCarrier->save();

        $product = Product::factory()->create();
        Livewire::test(ShopProduct::class, ['product' => $product])
            ->call('addToCart', $product->id);

        $this->get(route('checkout'))
            ->assertSee($shippingCarrier->name);
    }

    /** @test */
    public function dont_see_disabled_payment_methods()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $this->actingAs(User::factory()->create());

        $paymentMethod = PaymentMethod::factory()->create();
        $paymentMethod->status = 0;
        $paymentMethod->save();

        $product = Product::factory()->create();
        Livewire::test(ShopProduct::class, ['product' => $product])
            ->call('addToCart', $product->id);

        $this->get(route('checkout'))
            ->assertDontSee($paymentMethod->name);
    }

    /** @test */
    public function dont_see_disabled_shipping_carriers()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $this->actingAs(User::factory()->create());

        $shippingCarrier = ShippingCarrier::factory()->create();
        $shippingCarrier->status = 0;
        $shippingCarrier->save();

        $product = Product::factory()->create();
        Livewire::test(ShopProduct::class, ['product' => $product])
            ->call('addToCart', $product->id);

        $this->get(route('checkout'))
            ->assertDontSee($shippingCarrier->name);
    }

    /** @test */
    public function dont_see_checkout_with_empty_cart()
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('checkout'))
            ->assertRedirect(route('products'));
    }

    /** @test */
    public function shipping_price_is_added_in_total_price()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $this->actingAs(User::factory()->create());

        $shippingCarrier = ShippingCarrier::factory()->create();
        $shippingCarrier->status = 1;
        $shippingCarrier->price = 20;
        $shippingCarrier->save();

        $product = Product::factory()->create();
        $product->price = 123;
        $product->save();

        Livewire::test(ShopProduct::class, ['product' => $product])
            ->call('addToCart', $product->id);

        $total = $product->price + $shippingCarrier->price;

        Livewire::test(Checkout::class)
            ->assertSee(number_format($product->price, 2, ',', '.') . getenv('CURRENCY_SIGN'))
            ->set('shippingCarrierId', $shippingCarrier->id)
            ->assertSee(number_format($total, 2, ',', '.') . getenv('CURRENCY_SIGN'));
    }

    /** @test */
    public function payment_method_price_is_added_in_total_price()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $this->actingAs(User::factory()->create());

        $paymentMethod = PaymentMethod::factory()->create();
        $paymentMethod->status = 1;
        $paymentMethod->price = 20;
        $paymentMethod->save();

        $product = Product::factory()->create();
        $product->price = 123;
        $product->save();

        Livewire::test(ShopProduct::class, ['product' => $product])
            ->call('addToCart', $product->id);

        $total = $product->price + $paymentMethod->price;

        Livewire::test(Checkout::class)
            ->assertSee(number_format($product->price, 2, ',', '.') . getenv('CURRENCY_SIGN'))
            ->set('paymentMethodId', $paymentMethod->id)
            ->assertSee(number_format($total, 2, ',', '.') . getenv('CURRENCY_SIGN'));
    }

    /** @test */
    public function see_saved_shipping_addresses()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        Livewire::test(ShopProduct::class, ['product' => $product])
            ->call('addToCart', $product->id);

        $address = $user->addresses()->factory->create();

        Livewire::test(Checkout::class)
            ->assertSee($address->getText());
    }



    /** @test */
    public function see_saved_invoicing_addresses()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        Livewire::test(ShopProduct::class, ['product' => $product])
            ->call('addToCart', $product->id);

        $address = $user->invoiceAddresses()->factory->create();

        Livewire::test(Checkout::class)
            ->assertSee($address->getText());
    }
}
