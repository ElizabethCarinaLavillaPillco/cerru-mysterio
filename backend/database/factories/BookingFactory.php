<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Client;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * FACTORY: BookingFactory
 * 
 * ¿QUÉ ES? Generador de reservas de prueba
 * ¿CUÁNDO SE USA? Para simular historial de ventas
 * ¿CÓMO SE USA? Booking::factory()->count(100)->create()
 * 
 * UBICACIÓN: database/factories/BookingFactory.php
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $adults = fake()->numberBetween(1, 4);
        $children = fake()->numberBetween(0, 3);
        $infants = fake()->numberBetween(0, 2);
        $totalTravelers = $adults + $children + $infants;

        $tour = Tour::inRandomOrder()->first();
        $startDate = fake()->dateTimeBetween('now', '+6 months');
        $endDate = (clone $startDate)->modify("+{$tour->duration_days} days");

        $tourPrice = $tour->discount_price ?? $tour->base_price;
        $totalAmount = $tourPrice * $adults + ($tourPrice * 0.7 * $children);
        
        $paidAmount = fake()->randomElement([
            $totalAmount, // Pagado completo
            $totalAmount * 0.5, // 50% pagado
            0 // Sin pagar aún
        ]);

        $balance = $totalAmount - $paidAmount;

        // Determinar estados basados en fechas y pagos
        $isPast = $startDate < now();
        
        if ($isPast) {
            $status = 'completed';
            $paymentStatus = 'paid';
            $paidAmount = $totalAmount;
            $balance = 0;
        } else {
            $status = fake()->randomElement(['pending', 'confirmed', 'confirmed']);
            $paymentStatus = $paidAmount >= $totalAmount ? 'paid' : ($paidAmount > 0 ? 'partial' : 'pending');
        }

        return [
            'uuid' => Str::uuid(),
            'booking_number' => 'BK-' . strtoupper(Str::random(8)),
            
            'user_id' => User::inRandomOrder()->first()?->id,
            'client_id' => Client::inRandomOrder()->first()?->id ?? 1,
            'tour_id' => $tour->id,
            
            'start_date' => $startDate,
            'end_date' => $endDate,
            
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'total_travelers' => $totalTravelers,
            
            'tour_price' => $tourPrice,
            'additional_services' => fake()->randomFloat(2, 0, 200),
            'discount' => fake()->randomFloat(2, 0, 100),
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'balance' => $balance,
            'currency' => 'USD',
            
            'status' => $status,
            'payment_status' => $paymentStatus,
            
            'special_requirements' => fake()->boolean(30) ? fake()->sentence() : null,
            'notes' => fake()->boolean(20) ? fake()->sentence() : null,
            
            'confirmed_at' => $status === 'confirmed' ? now()->subDays(fake()->numberBetween(1, 10)) : null,
            'cancelled_at' => null,
            
            'assigned_to' => User::where('status', 'active')->inRandomOrder()->first()?->id,
        ];
    }

    /**
     * Estado: Reserva confirmada
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'paid_amount' => $attributes['total_amount'],
            'balance' => 0,
            'confirmed_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }

    /**
     * Estado: Reserva pendiente
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
            'paid_amount' => 0,
            'balance' => $attributes['total_amount'],
            'confirmed_at' => null,
        ]);
    }

    /**
     * Estado: Reserva cancelada
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => now()->subDays(fake()->numberBetween(1, 30)),
            'cancellation_reason' => fake()->sentence(),
        ]);
    }
}