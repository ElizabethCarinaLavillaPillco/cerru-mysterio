<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

/**
 * SEEDER: RoleSeeder
 * 
 * ¿QUÉ HACE? Crea los roles y permisos del sistema
 * ¿CUÁNDO SE USA? En la primera instalación
 * ¿CÓMO SE USA? php artisan db:seed --class=RoleSeeder
 * 
 * ROLES DEL SISTEMA:
 * - Super Admin: Control total
 * - Admin: Gestión completa
 * - Sales Agent: Ventas y clientes
 * - Support Agent: Atención al cliente
 * - Cliente: Acceso limitado
 * 
 * UBICACIÓN: database/seeders/RoleSeeder.php
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tablas (solo en desarrollo)
        if (app()->environment('local')) {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Role::truncate();
            Permission::truncate();
            \DB::table('permission_role')->truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // ==================== PERMISOS ====================
        $permissions = [
            // Tours
            ['name' => 'Ver Tours', 'slug' => 'tours.view'],
            ['name' => 'Crear Tours', 'slug' => 'tours.create'],
            ['name' => 'Editar Tours', 'slug' => 'tours.edit'],
            ['name' => 'Eliminar Tours', 'slug' => 'tours.delete'],
            ['name' => 'Publicar Tours', 'slug' => 'tours.publish'],

            // Reservas
            ['name' => 'Ver Reservas', 'slug' => 'bookings.view'],
            ['name' => 'Crear Reservas', 'slug' => 'bookings.create'],
            ['name' => 'Editar Reservas', 'slug' => 'bookings.edit'],
            ['name' => 'Cancelar Reservas', 'slug' => 'bookings.cancel'],
            ['name' => 'Confirmar Reservas', 'slug' => 'bookings.confirm'],

            // Clientes
            ['name' => 'Ver Clientes', 'slug' => 'clients.view'],
            ['name' => 'Crear Clientes', 'slug' => 'clients.create'],
            ['name' => 'Editar Clientes', 'slug' => 'clients.edit'],
            ['name' => 'Eliminar Clientes', 'slug' => 'clients.delete'],

            // Pagos
            ['name' => 'Ver Pagos', 'slug' => 'payments.view'],
            ['name' => 'Procesar Pagos', 'slug' => 'payments.process'],
            ['name' => 'Reembolsar Pagos', 'slug' => 'payments.refund'],

            // Reportes
            ['name' => 'Ver Reportes', 'slug' => 'reports.view'],
            ['name' => 'Exportar Reportes', 'slug' => 'reports.export'],

            // Usuarios
            ['name' => 'Ver Usuarios', 'slug' => 'users.view'],
            ['name' => 'Crear Usuarios', 'slug' => 'users.create'],
            ['name' => 'Editar Usuarios', 'slug' => 'users.edit'],
            ['name' => 'Eliminar Usuarios', 'slug' => 'users.delete'],

            // Configuración
            ['name' => 'Ver Configuración', 'slug' => 'settings.view'],
            ['name' => 'Editar Configuración', 'slug' => 'settings.edit'],

            // Atención al Cliente
            ['name' => 'Ver Conversaciones', 'slug' => 'conversations.view'],
            ['name' => 'Responder Conversaciones', 'slug' => 'conversations.reply'],
            ['name' => 'Asignar Conversaciones', 'slug' => 'conversations.assign'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $this->command->info('✅ Permisos creados: ' . count($permissions));

        // ==================== ROLES ====================

        // 1. SUPER ADMIN - Todos los permisos
        $superAdmin = Role::create([
            'name' => 'Super Administrador',
            'slug' => 'super_admin',
            'description' => 'Control total del sistema',
        ]);
        $superAdmin->permissions()->attach(Permission::all());
        $this->command->info('✅ Rol: Super Admin creado con todos los permisos');

        // 2. ADMIN - Casi todos los permisos excepto configuración crítica
        $admin = Role::create([
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Gestión completa del negocio',
        ]);
        $adminPermissions = Permission::whereNotIn('slug', ['users.delete', 'settings.edit'])->get();
        $admin->permissions()->attach($adminPermissions);
        $this->command->info('✅ Rol: Admin creado');

        // 3. SALES AGENT - Ventas y clientes
        $salesAgent = Role::create([
            'name' => 'Agente de Ventas',
            'slug' => 'sales_agent',
            'description' => 'Gestión de ventas y clientes',
        ]);
        $salesPermissions = Permission::whereIn('slug', [
            'tours.view',
            'bookings.view', 'bookings.create', 'bookings.edit', 'bookings.confirm',
            'clients.view', 'clients.create', 'clients.edit',
            'payments.view', 'payments.process',
            'reports.view',
        ])->get();
        $salesAgent->permissions()->attach($salesPermissions);
        $this->command->info('✅ Rol: Sales Agent creado');

        // 4. SUPPORT AGENT - Atención al cliente
        $supportAgent = Role::create([
            'name' => 'Agente de Soporte',
            'slug' => 'support_agent',
            'description' => 'Atención al cliente',
        ]);
        $supportPermissions = Permission::whereIn('slug', [
            'tours.view',
            'bookings.view', 'bookings.edit',
            'clients.view', 'clients.edit',
            'conversations.view', 'conversations.reply', 'conversations.assign',
        ])->get();
        $supportAgent->permissions()->attach($supportPermissions);
        $this->command->info('✅ Rol: Support Agent creado');

        // 5. CLIENTE - Acceso básico
        $client = Role::create([
            'name' => 'Cliente',
            'slug' => 'client',
            'description' => 'Usuario cliente con acceso limitado',
        ]);
        $clientPermissions = Permission::whereIn('slug', [
            'tours.view',
            'bookings.view', 'bookings.create',
        ])->get();
        $client->permissions()->attach($clientPermissions);
        $this->command->info('✅ Rol: Cliente creado');

        $this->command->info('');
        $this->command->info('🎉 Sistema de roles y permisos configurado correctamente!');
    }
}