<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomDigit(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->phoneNumber(),
            'full_name' => fake()->name(),
            'is_email_verified' => true,
            'is_phone_verified' => true,
            'socials' => [],
            'broker_license' => (object) [
                'id' => fake()->unique()->randomDigit(),
                'is_license_verified' => true,
                'is_license_expired' => false,
                'license_number' => fake()->numerify('#######')
            ],
            'mutuals_count' => fake()->randomDigitNotZero(),
            'connections_count' => fake()->randomDigitNotZero(),
            'pending_invitations_count' => fake()->randomDigitNotZero(),
            'request_invitations_count' => fake()->randomDigitNotZero()
        ];
    }

    /**
     * Indicate that the model's email address and phone number should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'phone_number_verified_at' => null,
        ]);
    }
}
