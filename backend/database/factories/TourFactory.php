<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * FACTORY: TourFactory
 * 
 * ¿QUÉ ES? Generador de tours de prueba
 * ¿CUÁNDO SE USA? Para poblar la BD con tours de prueba
 * ¿CÓMO SE USA? Tour::factory()->count(50)->create()
 * 
 * UBICACIÓN: database/factories/TourFactory.php
 */
class TourFactory extends Factory
{
    protected $model = Tour::class;

    public function definition(): array
    {
        $title = fake()->randomElement([
            'Machu Picchu Místico',
            'Expedición Amazónica',
            'Ruta del Valle Sagrado',
            'Aventura en los Andes',
            'Tour Gastronómico Lima',
            'Camino Inca Clásico',
            'Lineas de Nazca Aéreas',
            'Lago Titicaca Express',
            'Cañón del Colca',
            'Selva de Tambopata'
        ]) . ' ' . fake()->numberBetween(1, 20);

        $slug = Str::slug($title);
        
        $durationDays = fake()->numberBetween(1, 15);
        $durationNights = $durationDays - 1;

        $basePrice = fake()->randomFloat(2, 150, 2500);
        $hasDiscount = fake()->boolean(30); // 30% de probabilidad de descuento

        return [
            'uuid' => Str::uuid(),
            'slug' => $slug,
            'title' => $title,
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(20),
            
            'category_id' => TourCategory::inRandomOrder()->first()?->id ?? 1,
            'destination_id' => Destination::inRandomOrder()->first()?->id ?? 1,
            
            'duration_days' => $durationDays,
            'duration_nights' => $durationNights,
            
            'difficulty_level' => fake()->randomElement(['easy', 'moderate', 'challenging', 'difficult']),
            'max_group_size' => fake()->numberBetween(10, 30),
            'min_group_size' => fake()->numberBetween(2, 8),
            
            'base_price' => $basePrice,
            'discount_price' => $hasDiscount ? $basePrice * 0.85 : null,
            'currency' => 'USD',
            
            'featured' => fake()->boolean(20), // 20% featured
            'featured_order' => fake()->numberBetween(0, 100),
            
            'status' => fake()->randomElement(['draft', 'published', 'published', 'published']), // Más publicados
            'published_at' => fake()->boolean(80) ? now()->subDays(fake()->numberBetween(1, 90)) : null,
            
            'meta_title' => $title . ' - Peru Mysterious Travel',
            'meta_description' => fake()->sentence(15),
            'meta_keywords' => implode(', ', fake()->words(5)),
            
            'views_count' => fake()->numberBetween(0, 5000),
            'bookings_count' => fake()->numberBetween(0, 150),
        ];
    }

    /**
     * Estado: Tour destacado
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
            'featured_order' => fake()->numberBetween(1, 10),
        ]);
    }

    /**
     * Estado: Tour publicado
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now()->subDays(fake()->numberBetween(1, 60)),
        ]);
    }

    /**
     * Estado: Tour borrador
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Estado: Tour con descuento
     */
    public function discounted(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'discount_price' => $attributes['base_price'] * 0.80,
            ];
        });
    }
}