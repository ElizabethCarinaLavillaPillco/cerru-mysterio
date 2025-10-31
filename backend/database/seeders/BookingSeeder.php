<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingTraveler;
use App\Models\BookingStatusHistory;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * SEEDER: BookingSeeder
 * UBICACIÓN: database/seeders/BookingSeeder.php
 */
class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Crear métodos de pago si no existen
        $paymentMethods = [
            ['name' => 'Tarjeta de Crédito/Débito', 'slug' => 'card', 'type' => 'card', 'is_active' => true],
            ['name' => 'Transferencia Bancaria', 'slug' => 'bank_transfer', 'type' => 'bank_transfer', 'is_active' => true],
            ['name' => 'PayPal', 'slug' => 'paypal', 'type' => 'paypal', 'is_active' => true],
            ['name' => 'Efectivo', 'slug' => 'cash', 'type' => 'cash', 'is_active' => true],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::firstOrCreate(['slug' => $method['slug']], $method);
        }

        // Crear 100 reservas
        $bookings = Booking::factory()->count(100)->create();

        foreach ($bookings as $booking) {
            // ==================== VIAJEROS ====================
            for ($i = 1; $i <= $booking->adults; $i++) {
                BookingTraveler::create([
                    'booking_id' => $booking->id,
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'dni' => fake()->numerify('########'),
                    'passport' => null,
                    'birth_date' => fake()->dateTimeBetween('-60 years', '-18 years'),
                    'age' => rand(18, 60),
                    'nationality' => fake()->randomElement(['Peruana', 'Estadounidense', 'Española']),
                    'type' => 'adult',
                ]);
            }

            for ($i = 1; $i <= $booking->children; $i++) {
                BookingTraveler::create([
                    'booking_id' => $booking->id,
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'dni' => fake()->numerify('########'),
                    'passport' => null,
                    'birth_date' => fake()->dateTimeBetween('-12 years', '-3 years'),
                    'age' => rand(3, 12),
                    'nationality' => 'Peruana',
                    'type' => 'child',
                ]);
            }

            // ==================== HISTORIAL DE ESTADO ====================
            BookingStatusHistory::create([
                'booking_id' => $booking->id,
                'old_status' => null,
                'new_status' => 'pending',
                'notes' => 'Reserva creada',
                'changed_by' => User::inRandomOrder()->first()->id,
                'created_at' => $booking->created_at,
            ]);

            if ($booking->status !== 'pending') {
                BookingStatusHistory::create([
                    'booking_id' => $booking->id,
                    'old_status' => 'pending',
                    'new_status' => $booking->status,
                    'notes' => 'Estado actualizado',
                    'changed_by' => User::inRandomOrder()->first()->id,
                    'created_at' => $booking->created_at->addHours(rand(1, 48)),
                ]);
            }

            // ==================== PAGOS ====================
            if ($booking->paid_amount > 0) {
                $paymentMethod = PaymentMethod::inRandomOrder()->first();
                
                Payment::create([
                    'uuid' => \Illuminate\Support\Str::uuid(),
                    'transaction_id' => 'TRX-' . strtoupper(\Illuminate\Support\Str::random(10)),
                    'booking_id' => $booking->id,
                    'payment_method_id' => $paymentMethod->id,
                    'amount' => $booking->paid_amount,
                    'currency' => 'USD',
                    'status' => 'completed',
                    'payment_gateway' => $paymentMethod->type === 'card' ? 'stripe' : null,
                    'paid_at' => now()->subDays(rand(1, 30)),
                    'processed_by' => User::inRandomOrder()->first()->id,
                ]);
            }
        }

        $this->command->info("✅ {$bookings->count()} reservas creadas con viajeros, historial y pagos");
    }
}






