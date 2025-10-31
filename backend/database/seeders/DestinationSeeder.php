<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

/**
 * SEEDER: DestinationSeeder
 * 
 * ¿QUÉ HACE? Crea los principales destinos turísticos de Perú
 * UBICACIÓN: database/seeders/DestinationSeeder.php
 */
class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Machu Picchu',
                'slug' => 'machu-picchu',
                'country' => 'Perú',
                'region' => 'Cusco',
                'description' => 'La ciudadela inca más famosa del mundo, una de las 7 maravillas modernas.',
                'climate' => 'Templado húmedo, con lluvias de noviembre a marzo',
                'best_season' => 'Abril a Octubre',
                'latitude' => -13.1631,
                'longitude' => -72.5450,
                'featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'Cusco',
                'slug' => 'cusco',
                'country' => 'Perú',
                'region' => 'Cusco',
                'description' => 'La capital del Imperio Inca, conocida como el ombligo del mundo.',
                'climate' => 'Clima seco y templado con noches frías',
                'best_season' => 'Mayo a Septiembre',
                'latitude' => -13.5319,
                'longitude' => -71.9675,
                'featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'Lima',
                'slug' => 'lima',
                'country' => 'Perú',
                'region' => 'Lima',
                'description' => 'Capital del Perú, ciudad cosmopolita con excelente gastronomía.',
                'climate' => 'Templado, húmedo con garúa en invierno',
                'best_season' => 'Todo el año',
                'latitude' => -12.0464,
                'longitude' => -77.0428,
                'featured' => true,
                'order' => 3,
            ],
            [
                'name' => 'Arequipa',
                'slug' => 'arequipa',
                'country' => 'Perú',
                'region' => 'Arequipa',
                'description' => 'La Ciudad Blanca, famosa por su arquitectura colonial en sillar.',
                'climate' => 'Seco y soleado todo el año',
                'best_season' => 'Todo el año',
                'latitude' => -16.4090,
                'longitude' => -71.5375,
                'featured' => true,
                'order' => 4,
            ],
            [
                'name' => 'Puno - Lago Titicaca',
                'slug' => 'puno-lago-titicaca',
                'country' => 'Perú',
                'region' => 'Puno',
                'description' => 'El lago navegable más alto del mundo, con islas flotantes.',
                'climate' => 'Frío con sol intenso durante el día',
                'best_season' => 'Abril a Noviembre',
                'latitude' => -15.8402,
                'longitude' => -70.0219,
                'featured' => true,
                'order' => 5,
            ],
            [
                'name' => 'Iquitos - Amazonía',
                'slug' => 'iquitos-amazonia',
                'country' => 'Perú',
                'region' => 'Loreto',
                'description' => 'Puerta de entrada a la selva amazónica peruana.',
                'climate' => 'Tropical cálido y húmedo',
                'best_season' => 'Mayo a Octubre',
                'latitude' => -3.7437,
                'longitude' => -73.2516,
                'featured' => true,
                'order' => 6,
            ],
            [
                'name' => 'Nazca',
                'slug' => 'nazca',
                'country' => 'Perú',
                'region' => 'Ica',
                'description' => 'Famosa por sus enigmáticas líneas y geoglifos.',
                'climate' => 'Desértico, seco y cálido',
                'best_season' => 'Todo el año',
                'latitude' => -14.8310,
                'longitude' => -74.9277,
                'featured' => false,
                'order' => 7,
            ],
            [
                'name' => 'Paracas',
                'slug' => 'paracas',
                'country' => 'Perú',
                'region' => 'Ica',
                'description' => 'Reserva nacional con playas, islas y fauna marina.',
                'climate' => 'Cálido y seco',
                'best_season' => 'Todo el año',
                'latitude' => -13.8339,
                'longitude' => -76.2513,
                'featured' => false,
                'order' => 8,
            ],
            [
                'name' => 'Huaraz - Cordillera Blanca',
                'slug' => 'huaraz-cordillera-blanca',
                'country' => 'Perú',
                'region' => 'Áncash',
                'description' => 'Paraíso para el trekking y el montañismo.',
                'climate' => 'Frío en alturas, templado en valles',
                'best_season' => 'Mayo a Septiembre',
                'latitude' => -9.5265,
                'longitude' => -77.5267,
                'featured' => false,
                'order' => 9,
            ],
            [
                'name' => 'Cañón del Colca',
                'slug' => 'canon-del-colca',
                'country' => 'Perú',
                'region' => 'Arequipa',
                'description' => 'Uno de los cañones más profundos del mundo.',
                'climate' => 'Frío en las noches, templado de día',
                'best_season' => 'Abril a Noviembre',
                'latitude' => -15.6000,
                'longitude' => -71.8833,
                'featured' => false,
                'order' => 10,
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }

        $this->command->info('✅ ' . count($destinations) . ' destinos creados');
    }
}