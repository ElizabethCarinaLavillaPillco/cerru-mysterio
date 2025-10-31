<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

/**
 * SEEDER: ClientSeeder
 * UBICACIÓN: database/seeders/ClientSeeder.php
 */
class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 30 clientes
        $clients = Client::factory()->count(30)->create();

        $this->command->info("✅ {$clients->count()} clientes creados");
    }
}