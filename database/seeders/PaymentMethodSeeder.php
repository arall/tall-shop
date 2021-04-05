<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::firstOrCreate([
            'status' => 1,
            'name' => 'Paypal',
            'type' => 'paypal',
        ]);

        PaymentMethod::firstOrCreate([
            'status' => 1,
            'name' => 'Credit Card',
            'type' => 'stripe',
        ]);
    }
}
