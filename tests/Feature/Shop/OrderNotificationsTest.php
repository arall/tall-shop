<?php

namespace Tests\Feature\Shop;

use Illuminate\Support\Facades\Notification;
use App\Models\Product;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ShippingCarrier;
use App\Models\User;
use App\Notifications\Users\Orders\Confirmation;
use App\Notifications\Users\Orders\InPreparation;
use App\Notifications\Users\Orders\Shipped;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\ProductTypeSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ShippingCarrierSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderNotificationsTest extends TestCase
{
    use RefreshDatabase;

    private $order;

    private $user;

    private function init()
    {
        Notification::fake();

        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);
        $this->seed(ShippingCarrierSeeder::class);
        $this->seed(PaymentMethodSeeder::class);

        $product = Product::factory()->create();

        $this->user = $user = User::factory()
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

        $this->order = Order::create(
            $this->user,
            $user->addresses()->first(),
            null,
            ShippingCarrier::first(),
            PaymentMethod::first(),
            0.21,
            $cart
        );
    }

    /** @test */
    public function users_recieve_order_confirmation_email_after_order_is_paid()
    {
        $this->init();

        $this->order->status = 1;
        $this->order->save();

        Notification::assertSentTo([$this->user], Confirmation::class);
    }

    /** @test */
    public function users_recieve_order_in_preparation_email()
    {
        $this->init();

        $this->order->status = 2;
        $this->order->save();

        Notification::assertSentTo([$this->user], InPreparation::class);
    }

    /** @test */
    public function users_recieve_order_shipped_email()
    {
        $this->init();

        $this->order->status = 3;
        $this->order->save();

        Notification::assertSentTo([$this->user], Shipped::class);
    }
}
