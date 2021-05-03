<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => 1,
            'type' => array_rand(['Paypal', 'Credit Card'], 1),
            'name' => $this->faker->unique()->company,
            'price' => rand(0, 1) ? $this->faker->randomFloat(2, 5, 10) : null,
            'price_percent' => rand(0, 1) ? $this->faker->numberBetween(10, 20) : null,
        ];
    }
}
