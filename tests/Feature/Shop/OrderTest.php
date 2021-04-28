<?php

namespace Tests\Feature\Shop;

use Illuminate\Support\Facades\Notification;
use App\Models\Product;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ShippingCarrier;
use App\Models\User;
use App\Notifications\Users\OrderConfirmation;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\ProductTypeSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ShippingCarrierSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_recieve_order_confirmation_email_after_order_is_paid()
    {
        Notification::fake();

        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);
        $this->seed(ShippingCarrierSeeder::class);
        $this->seed(PaymentMethodSeeder::class);

        $product = Product::factory()->create();
        $user = User::factory()
            ->hasAddresses(1)
            ->hasInvoiceAddresses(1)
            ->create();

        $cart = [
            [
                'product_id' => $product->id,
                'option_ids' => [],
                'units' => 1,
            ]
        ];

        $order = Order::create(
            $user,
            $user->addresses()->first(),
            $user->invoiceAddresses()->first(),
            ShippingCarrier::first(),
            PaymentMethod::first(),
            $cart
        );

        $order->setAsPaid();

        Notification::assertSentTo([$user], OrderConfirmation::class);
    }
}
