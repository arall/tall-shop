<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Product::truncate();
        ProductImage::truncate();
        ProductVariantOption::truncate();

        Storage::deleteDirectory('public/images/products/');
        Storage::makeDirectory('public/images/products/original');

        $products = Product::factory()
            ->hasImages(3)
            ->count(10)
            ->create();

        foreach ($products as $product) {
            if (rand(0, 1) == 1) {
                $categories = ProductCategory::inRandomOrder()->limit(rand(1, 2))->pluck('id');
                if ($categories) {
                    $product->categories()->sync($categories);
                }
            }

            if (rand(0, 1) == 1) {
                $attribute = ProductVariant::inRandomOrder()->first();
                for ($i = 0; $i <= rand(2, 4); $i++) {
                    ProductVariantOption::create([
                        'product_id' => $product->id,
                        'product_variant_id' => $attribute->id,
                        'name' => $faker->unique()->word,
                        'price' => $i == 0 ? 0 : $faker->numberBetween(10, 100),
                        'sku' => $faker->unique()->isbn13,
                        'weight' => $faker->randomDigitNotNull,
                    ]);
                }
            }
        }
    }
}
