<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\TourImage;
use App\Models\TourItinerary;
use App\Models\TourPrice;
use App\Models\TourInclusion;
use Illuminate\Database\Seeder;

/**
 * SEEDER: TourSeeder
 * 
 * ¿QUÉ HACE? Crea tours completos con todas sus relaciones
 * UBICACIÓN: database/seeders/TourSeeder.php
 */
class TourSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 50 tours usando factory
        $tours = Tour::factory()->count(50)->create();

        foreach ($tours as $tour) {
            // ==================== IMÁGENES ====================
            // 1 imagen de portada + 3-6 imágenes de galería
            TourImage::create([
                'tour_id' => $tour->id,
                'path' => 'tours/default-cover.jpg',
                'type' => 'cover',
                'alt_text' => $tour->title,
                'is_cover' => true,
                'order' => 0,
            ]);

            $galleryCount = rand(3, 6);
            for ($i = 1; $i <= $galleryCount; $i++) {
                TourImage::create([
                    'tour_id' => $tour->id,
                    'path' => "tours/gallery-{$i}.jpg",
                    'type' => 'gallery',
                    'alt_text' => "{$tour->title} - Imagen {$i}",
                    'is_cover' => false,
                    'order' => $i,
                ]);
            }

            // ==================== ITINERARIO ====================
            for ($day = 1; $day <= $tour->duration_days; $day++) {
                TourItinerary::create([
                    'tour_id' => $tour->id,
                    'day' => $day,
                    'title' => "Día {$day}: " . fake()->words(3, true),
                    'description' => fake()->paragraph(4),
                    'activities' => json_encode([
                        fake()->sentence(),
                        fake()->sentence(),
                        fake()->sentence(),
                    ]),
                    'accommodation' => $day < $tour->duration_days ? fake()->randomElement([
                        'Hotel 3 estrellas',
                        'Hotel 4 estrellas',
                        'Lodge',
                        'Campamento'
                    ]) : null,
                    'meals' => fake()->randomElement([
                        'Desayuno, Almuerzo, Cena',
                        'Desayuno, Almuerzo',
                        'Desayuno',
                    ]),
                ]);
            }

            // ==================== PRECIOS POR TEMPORADA ====================
            // Temporada regular
            TourPrice::create([
                'tour_id' => $tour->id,
                'season' => 'regular',
                'adult_price' => $tour->base_price,
                'child_price' => $tour->base_price * 0.7,
                'infant_price' => $tour->base_price * 0.3,
                'min_group_for_discount' => 5,
                'group_discount_percentage' => 10.00,
            ]);

            // Temporada alta (más caro)
            TourPrice::create([
                'tour_id' => $tour->id,
                'season' => 'high',
                'adult_price' => $tour->base_price * 1.2,
                'child_price' => $tour->base_price * 0.7 * 1.2,
                'infant_price' => $tour->base_price * 0.3 * 1.2,
                'min_group_for_discount' => 5,
                'group_discount_percentage' => 8.00,
                'valid_from' => now()->addMonth(2),
                'valid_to' => now()->addMonth(4),
            ]);

            // ==================== INCLUSIONES ====================
            $inclusions = [
                'Transporte turístico',
                'Guía profesional bilingüe',
                'Entradas a sitios turísticos',
                'Alimentación según itinerario',
                'Hotel ' . rand(3, 4) . ' estrellas',
                'Seguro de viaje',
            ];

            foreach ($inclusions as $index => $inclusion) {
                TourInclusion::create([
                    'tour_id' => $tour->id,
                    'type' => 'included',
                    'description' => $inclusion,
                    'order' => $index,
                ]);
            }

            // ==================== EXCLUSIONES ====================
            $exclusions = [
                'Vuelos internacionales',
                'Gastos personales',
                'Propinas',
                'Bebidas alcohólicas',
            ];

            foreach ($exclusions as $index => $exclusion) {
                TourInclusion::create([
                    'tour_id' => $tour->id,
                    'type' => 'excluded',
                    'description' => $exclusion,
                    'order' => $index,
                ]);
            }
        }

        $this->command->info("✅ {$tours->count()} tours creados con imágenes, itinerarios y precios");
    }
}