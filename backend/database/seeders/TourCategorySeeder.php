<?php

namespace Database\Seeders;

use App\Models\TourCategory;
use Illuminate\Database\Seeder;

/**
 * SEEDER: TourCategorySeeder
 * 
 * ¿QUÉ HACE? Crea las categorías de tours
 * UBICACIÓN: database/seeders/TourCategorySeeder.php
 */
class TourCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Aventura',
                'slug' => 'aventura',
                'description' => 'Tours para los amantes de la adrenalina y la naturaleza extrema',
                'icon' => '🏔️',
                'color' => '#FF5722',
                'order' => 1,
            ],
            [
                'name' => 'Cultural',
                'slug' => 'cultural',
                'description' => 'Descubre la rica historia y cultura peruana',
                'icon' => '🏛️',
                'color' => '#9C27B0',
                'order' => 2,
            ],
            [
                'name' => 'Naturaleza',
                'slug' => 'naturaleza',
                'description' => 'Explora la biodiversidad del Perú',
                'icon' => '🌿',
                'color' => '#4CAF50',
                'order' => 3,
            ],
            [
                'name' => 'Gastronómico',
                'slug' => 'gastronomico',
                'description' => 'Experiencias culinarias únicas',
                'icon' => '🍽️',
                'color' => '#FF9800',
                'order' => 4,
            ],
            [
                'name' => 'Trekking',
                'slug' => 'trekking',
                'description' => 'Caminatas y expediciones por los Andes',
                'icon' => '🥾',
                'color' => '#795548',
                'order' => 5,
            ],
            [
                'name' => 'Místico',
                'slug' => 'mistico',
                'description' => 'Tours espirituales y ceremonias ancestrales',
                'icon' => '🔮',
                'color' => '#673AB7',
                'order' => 6,
            ],
            [
                'name' => 'Playa',
                'slug' => 'playa',
                'description' => 'Destinos costeros y playas paradisíacas',
                'icon' => '🏖️',
                'color' => '#00BCD4',
                'order' => 7,
            ],
            [
                'name' => 'Familia',
                'slug' => 'familia',
                'description' => 'Tours diseñados para toda la familia',
                'icon' => '👨‍👩‍👧‍👦',
                'color' => '#E91E63',
                'order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            TourCategory::create($category);
        }

        $this->command->info('✅ ' . count($categories) . ' categorías de tours creadas');
    }
}