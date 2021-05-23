<?php

namespace Tests\Feature\Shop;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\ProductTypeSeeder;
use Database\Seeders\ProductCategorySeeder;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function invoice_is_generated_after_success_payment()
    {
        $this->seed(ProductTypeSeeder::class);
        $this->seed(ProductCategorySeeder::class);
    }
}
