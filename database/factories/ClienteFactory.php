<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition()
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
