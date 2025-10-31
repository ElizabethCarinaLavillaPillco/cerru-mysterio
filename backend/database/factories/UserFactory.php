<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * FACTORY: UserFactory
 * 
 * ¿QUÉ ES? Un molde para crear usuarios de prueba
 * ¿CUÁNDO SE USA? En seeders, tests, desarrollo
 * ¿CÓMO SE USA? User::factory()->count(10)->create()
 * 
 * UBICACIÓN: database/factories/UserFactory.php
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define el estado por defecto del modelo
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'), // Contraseña por defecto
            'phone' => fake()->phoneNumber(),
            'avatar' => null,
            'status' => fake()->randomElement(['active', 'inactive']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Estado: Usuario sin verificar email
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Estado: Usuario inactivo
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Estado: Usuario suspendido
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }
}