<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'payment_method' => $this->faker->randomElement(['card', 'konbini']),
            'post_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'building' => $this->faker->optional()->secondaryAddress(),
        ];
    }
}
