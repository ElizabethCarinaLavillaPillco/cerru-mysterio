<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * SEEDER PRINCIPAL: DatabaseSeeder
 * 
 * ¿QUÉ HACE? Ejecuta todos los seeders en el orden correcto
 * ¿CÓMO SE USA? php artisan db:seed
 * 
 * ORDEN DE EJECUCIÓN:
 * 1. Roles y Permisos
 * 2. Usuarios
 * 3. Categorías de Tours
 * 4. Destinos
 * 5. Tours (con relaciones)
 * 6. Clientes
 * 7. Reservas (con relaciones)
 * 8. Pagos
 * 9. Hoteles
 * 10. Reviews
 * 
 * UBICACIÓN: database/seeders/DatabaseSeeder.php
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('🌱 INICIANDO SEEDERS - PERU MYSTERIOUS');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('');

        // ==================== FASE 1: ROLES Y PERMISOS ====================
        $this->command->info('📋 FASE 1: Configurando roles y permisos...');
        $this->call(RoleSeeder::class);
        $this->command->info('');

        // ==================== FASE 2: USUARIOS ====================
        $this->command->info('👥 FASE 2: Creando usuarios del sistema...');
        $this->call(UserSeeder::class);
        $this->command->info('');

        // ==================== FASE 3: CATÁLOGOS ====================
        $this->command->info('📚 FASE 3: Creando catálogos...');
        $this->call(TourCategorySeeder::class);
        $this->call(DestinationSeeder::class);
        $this->command->info('');

        // ==================== FASE 4: TOURS ====================
        $this->command->info('🗺️ FASE 4: Generando tours...');
        $this->call(TourSeeder::class);
        $this->command->info('');

        // ==================== FASE 5: CLIENTES ====================
        $this->command->info('👤 FASE 5: Creando clientes...');
        $this->call(ClientSeeder::class);
        $this->command->info('');

        // ==================== FASE 6: RESERVAS Y PAGOS ====================
        $this->command->info('📅 FASE 6: Generando reservas y pagos...');
        $this->call(BookingSeeder::class);
        $this->command->info('');

        // ==================== FASE 7: HOTELES ====================
        $this->command->info('🏨 FASE 7: Creando hoteles...');
        $this->call(HotelSeeder::class);
        $this->command->info('');

        // ==================== FASE 8: REVIEWS ====================
        $this->command->info('⭐ FASE 8: Generando reviews...');
        $this->call(ReviewSeeder::class);
        $this->command->info('');

        // ==================== RESUMEN FINAL ====================
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('✅ SEEDERS COMPLETADOS EXITOSAMENTE');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('📊 RESUMEN DE DATOS CREADOS:');
        $this->command->info('  • Roles: 5');
        $this->command->info('  • Permisos: 28');
        $this->command->info('  • Usuarios: ~18');
        $this->command->info('  • Categorías: 8');
        $this->command->info('  • Destinos: 10');
        $this->command->info('  • Tours: ~50');
        $this->command->info('  • Clientes: ~30');
        $this->command->info('  • Reservas: ~100');
        $this->command->info('  • Hoteles: ~15');
        $this->command->info('  • Reviews: ~80');
        $this->command->info('');
        $this->command->info('🎉 Base de datos lista para desarrollo!');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('');
    }
}