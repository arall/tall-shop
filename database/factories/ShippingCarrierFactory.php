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
            'status' => 1,
            'name' => $this->faker->unique()->company,
            'price' => $this->faker->randomFloat(2, 5, 10),
            'price_kg' => $this->faker->randomFloat(2, 1, 5),
            'eta' => rand(1, 0) == 0 ? '24' : '72',
        ];
    }
}
