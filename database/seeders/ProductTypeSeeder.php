<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductType::firstOrCreate([
            'name' => 'Product',
        ]);
        ProductType::firstOrCreate([
            'name' => 'Subscription',
        ]);
    }
}
