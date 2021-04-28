<?php

namespace Database\Factories;

use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->name,
            'lastname' => $this->faker->lastName,
            'country' => $this->faker->country,
            'region' => $this->faker->state,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'zip' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
