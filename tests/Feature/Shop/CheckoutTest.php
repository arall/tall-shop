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
use App\Models\Order;
use Tests\TestCase;
use App\Helpers\Taxes;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\ShippingCarrierSeeder;

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

        $user = User::factory()->hasAddresses(1)->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        Livewire::test(ShopProduct::class, ['product' => $product])
            ->call('addToCart', $product->id);

        $address = $user->addresses()->first();

        Livewire::test(Checkout::class)
            ->assertSee($address->getText());
    }

    /** @test */
    public function see_saved_invoicing_addresses()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $user = User::factory()->hasAddresses(1)->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        Livewire::test(ShopProduct::class, ['product' => $product])
            ->call('addToCart', $product->id);

        $address = $user->addresses()->first();

        Livewire::test(Checkout::class)
            ->assertSee($address->getText());
    }

    /** @test */
    // TODO: Test product options
    public function can_create_an_order()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);

        $taxRatio = 0.21;
        config(['shop.taxes' => true]);
        config(['shop.product_price_contains_taxes' => true]);
        config(['shop.tax_ratio' => $taxRatio]);

        $user = User::factory()
            ->hasAddresses(1)
            ->hasInvoiceAddresses(1)
            ->create();
        $this->actingAs($user);

        $address = $user->addresses()->first();
        $invoiceAddress = $user->invoiceAddresses()->first();

        $shipping = ShippingCarrier::factory()->create(['price' => 123.50, 'price_kg' => null]);
        $paymentMethod = PaymentMethod::factory()->create(['price' => 321.50, 'price_percent' => null]);

        $product1 = Product::factory()->create();
        Livewire::test(ShopProduct::class, ['product' => $product1])
            ->call('addToCart', $product1->id);

        $product2 = Product::factory()->create();
        Livewire::test(ShopProduct::class, ['product' => $product2])
            ->call('addToCart', $product2->id);

        Livewire::test(Checkout::class)
            ->set('addressId', $address->id)
            ->set('invoiceAddressId', $invoiceAddress->id)
            ->set('shippingCarrierId', $shipping->id)
            ->set('paymentMethodId', $paymentMethod->id)
            ->call('save');

        $order = Order::first();

        $this->assertEquals($order->status, 0);
        $this->assertEquals($order->user->id, $user->id);
        $this->assertEquals($order->firstname, $user->name);
        $this->assertEquals($order->lastname, $address->lastname);
        $this->assertEquals($order->country, $address->country);
        $this->assertEquals($order->address, $address->address);
        $this->assertEquals($order->zip, $address->zip);
        $this->assertEquals($order->city, $address->city);
        $this->assertEquals($order->region, $address->region);
        $this->assertEquals($order->phone, $address->phone);
        $this->assertEquals($order->tax, $taxRatio);
        $this->assertEquals($order->shippingCarrier->id, $shipping->id);
        $this->assertEquals($order->paymentMethod->id, $paymentMethod->id);
        $this->assertEquals($order->shipping_price_untaxed, Taxes::calcPriceWithoutTax($shipping->price));
        $this->assertEquals($order->payment_method_price_untaxed, Taxes::calcPriceWithoutTax($paymentMethod->price));
        $this->assertEquals($order->shipping_price, Taxes::calcPriceWithTax($shipping->price, $taxRatio));
        $this->assertEquals($order->payment_method_price, Taxes::calcPriceWithTax($paymentMethod->price, $taxRatio));
        $this->assertEquals($order->products_price_untaxed, Taxes::calcPriceWithoutTax($product1->price + $product2->price));
        $this->assertEquals($order->products_price, Taxes::calcPriceWithTax($product1->price + $product2->price, $taxRatio));
        $this->assertEquals($order->total_price_untaxed, Taxes::calcPriceWithoutTax($product1->price + $product2->price + $shipping->price + $paymentMethod->price));
        $this->assertEquals($order->total_price, Taxes::calcPriceWithTax($product1->price + $product2->price + $shipping->price + $paymentMethod->price, $taxRatio));
    }
}
