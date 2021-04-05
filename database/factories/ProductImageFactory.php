<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $path = storage_path('app/public/images/products/original') . '/' . Str::random(8) . '.jpeg';
        file_put_contents($path, file_get_contents('https://picsum.photos/800/600'));

        return [
            'original_filename' => basename($path),
        ];
    }
}
