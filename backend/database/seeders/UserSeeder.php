<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * SEEDER: UserSeeder
 * 
 * ¿QUÉ HACE? Crea usuarios de prueba con diferentes roles
 * ¿CUÁNDO SE USA? Después de RoleSeeder
 * ¿CÓMO SE USA? php artisan db:seed --class=UserSeeder
 * 
 * USUARIOS CREADOS:
 * - 1 Super Admin
 * - 2 Admins
 * - 3 Sales Agents
 * - 2 Support Agents
 * - 10 Clientes
 * 
 * UBICACIÓN: database/seeders/UserSeeder.php
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Creando usuarios del sistema...');

        // Obtener roles
        $superAdminRole = Role::where('slug', 'super_admin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $salesRole = Role::where('slug', 'sales_agent')->first();
        $supportRole = Role::where('slug', 'support_agent')->first();
        $clientRole = Role::where('slug', 'client')->first();

        // ==================== SUPER ADMIN ====================
        $superAdmin = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Super Administrador',
            'email' => 'superadmin@perumysterious.com',
            'password' => Hash::make('password123'),
            'phone' => '+51 999 888 777',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $superAdmin->roles()->attach($superAdminRole);
        $this->command->info('✅ Super Admin creado: superadmin@perumysterious.com / password123');

        // ==================== ADMINS ====================
        $admin1 = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Carlos Administrador',
            'email' => 'admin@perumysterious.com',
            'password' => Hash::make('password123'),
            'phone' => '+51 999 888 666',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $admin1->roles()->attach($adminRole);

        $admin2 = User::create([
            'uuid' => Str::uuid(),
            'name' => 'María Gestora',
            'email' => 'maria.admin@perumysterious.com',
            'password' => Hash::make('password123'),
            'phone' => '+51 999 888 555',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $admin2->roles()->attach($adminRole);
        $this->command->info('✅ 2 Admins creados');

        // ==================== SALES AGENTS ====================
        $salesAgents = [
            ['name' => 'Juan Ventas', 'email' => 'juan.ventas@perumysterious.com'],
            ['name' => 'Ana López', 'email' => 'ana.ventas@perumysterious.com'],
            ['name' => 'Pedro Ramírez', 'email' => 'pedro.ventas@perumysterious.com'],
        ];

        foreach ($salesAgents as $agent) {
            $user = User::create([
                'uuid' => Str::uuid(),
                'name' => $agent['name'],
                'email' => $agent['email'],
                'password' => Hash::make('password123'),
                'phone' => '+51 999 ' . rand(100000, 999999),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $user->roles()->attach($salesRole);
        }
        $this->command->info('✅ 3 Sales Agents creados');

        // ==================== SUPPORT AGENTS ====================
        $supportAgents = [
            ['name' => 'Lucía Soporte', 'email' => 'lucia.support@perumysterious.com'],
            ['name' => 'Roberto Ayuda', 'email' => 'roberto.support@perumysterious.com'],
        ];

        foreach ($supportAgents as $agent) {
            $user = User::create([
                'uuid' => Str::uuid(),
                'name' => $agent['name'],
                'email' => $agent['email'],
                'password' => Hash::make('password123'),
                'phone' => '+51 999 ' . rand(100000, 999999),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $user->roles()->attach($supportRole);
        }
        $this->command->info('✅ 2 Support Agents creados');

        // ==================== CLIENTES ====================
        $clients = User::factory()
            ->count(10)
            ->create([
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

        foreach ($clients as $client) {
            $client->roles()->attach($clientRole);
        }
        $this->command->info('✅ 10 Clientes creados');

        // ==================== RESUMEN ====================
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('🎉 USUARIOS CREADOS EXITOSAMENTE');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('📧 CREDENCIALES DE ACCESO:');
        $this->command->info('');
        $this->command->info('Super Admin:');
        $this->command->info('  Email: superadmin@perumysterious.com');
        $this->command->info('  Pass:  password123');
        $this->command->info('');
        $this->command->info('Admin:');
        $this->command->info('  Email: admin@perumysterious.com');
        $this->command->info('  Pass:  password123');
        $this->command->info('');
        $this->command->info('Sales Agent:');
        $this->command->info('  Email: juan.ventas@perumysterious.com');
        $this->command->info('  Pass:  password123');
        $this->command->info('');
        $this->command->info('Support Agent:');
        $this->command->info('  Email: lucia.support@perumysterious.com');
        $this->command->info('  Pass:  password123');
        $this->command->info('═══════════════════════════════════════════');
    }
}