<?php

namespace Database\Factories;

use App\Models\ShippingCarrier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingCarrierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShippingCarrier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 5, 10),
            'price_kg' => $this->faker->randomFloat(2, 1, 5),
            'tax' => $this->faker->randomNumber(2),
            'eta' => rand(1, 0) == 0 ? '24' : '72',
        ];
    }
}
