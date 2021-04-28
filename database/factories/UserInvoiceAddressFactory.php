<?php

namespace Database\Factories;

use App\Models\UserInvoiceAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserInvoiceAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserInvoiceAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vat' => $this->faker->randomNumber,
            'name' => $this->faker->name,
            'country' => $this->faker->country,
            'region' => $this->faker->state,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'zip' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
