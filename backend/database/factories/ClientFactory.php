<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * FACTORY: ClientFactory
 * 
 * UBICACIÓN: database/factories/ClientFactory.php
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [
            'uuid' => Str::uuid(),
            'user_id' => fake()->boolean(60) ? User::inRandomOrder()->first()?->id : null,
            
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'dni' => fake()->boolean(70) ? fake()->numerify('########') : null,
            'passport' => fake()->boolean(30) ? strtoupper(fake()->bothify('??######')) : null,
            
            'birth_date' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'nationality' => fake()->randomElement(['Peruana', 'Estadounidense', 'Española', 'Argentina', 'Chilena', 'Brasileña']),
            
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'country' => fake()->randomElement(['Perú', 'USA', 'España', 'Argentina', 'Chile', 'Brasil']),
            'postal_code' => fake()->postcode(),
            
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            
            'preferences' => fake()->boolean(30) ? json_encode([
                'dietary' => fake()->randomElement(['none', 'vegetarian', 'vegan', 'gluten-free']),
                'accommodation' => fake()->randomElement(['standard', 'luxury']),
                'activities' => fake()->randomElement(['adventure', 'cultural', 'relaxing']),
            ]) : null,
            
            'notes' => fake()->boolean(20) ? fake()->sentence() : null,
            'status' => fake()->randomElement(['active', 'active', 'active', 'inactive']),
        ];
    }

    /**
     * Estado: Cliente activo con usuario vinculado
     */
    public function withUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory(),
            'status' => 'active',
        ]);
    }

    /**
     * Estado: Cliente VIP
     */
    public function vip(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'preferences' => json_encode([
                'dietary' => 'special',
                'accommodation' => 'luxury',
                'activities' => 'exclusive',
                'vip' => true,
            ]),
        ]);
    }
}