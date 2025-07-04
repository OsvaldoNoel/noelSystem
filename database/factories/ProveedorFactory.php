<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proveedors>
 */
class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			'tenant_id' => $this->faker->randomElement(['1','2','3','4','5']),
			'name' => $this->faker->lastname,
			'ruc' => $this->faker->numberBetween($min = 3000000, $max = 6000000),
			'dv' => $this->faker->randomDigit,			
			'phone' => $this->faker->phoneNumber,
			'email' => $this->faker->email,
			'adress' => $this->faker->streetAddress,
			'barrio' => $this->faker->state,
			'city' => $this->faker->city,
			'status' => $this->faker->randomElement(['1','0']),
        ];
    }
}
