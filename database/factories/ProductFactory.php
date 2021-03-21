<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type_id' => ProductType::inRandomOrder()->first()->id,
            'name' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'ref' => $this->faker->isbn13,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'tax' => $this->faker->randomNumber(2),
            'discount' => rand(1, 3) == 3 ? $this->faker->randomNumber(2) : null,
            'weight' => $this->faker->randomNumber(2),
            'quantity' => $this->faker->randomNumber(2),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Product $product) {
            //
        })->afterCreating(function (Product $product) {
            if ($product->images()->count()) {
                $product->cover()->associate($product->images()->first());
                $product->save();
            }
        });
    }
}
