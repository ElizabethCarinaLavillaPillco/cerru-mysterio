<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('booking_number')->unique();
            
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('tour_id')->constrained();
            
            $table->date('start_date');
            $table->date('end_date');
            
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->integer('total_travelers');
            
            $table->decimal('tour_price', 10, 2);
            $table->decimal('additional_services', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2);
            $table->string('currency')->default('USD');
            
            $table->enum('status', [
                'pending',
                'confirmed',
                'in_progress',
                'completed',
                'cancelled',
                'refunded'
            ])->default('pending');
            
            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid',
                'refunded'
            ])->default('pending');
            
            $table->text('special_requirements')->nullable();
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'start_date']);
            $table->index('booking_number');
            $table->index('client_id');
            $table->index('tour_id');
        });

        Schema::create('booking_travelers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            
            $table->string('first_name');
            $table->string('last_name');
            $table->string('dni')->nullable();
            $table->string('passport')->nullable();
            $table->date('birth_date');
            $table->integer('age');
            $table->string('nationality');
            $table->enum('type', ['adult', 'child', 'infant']);
            $table->text('special_requirements')->nullable();
            
            $table->timestamps();
        });

        Schema::create('booking_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('notes')->nullable();
            $table->foreignId('changed_by')->constrained('users');
            $table->timestamps();

            $table->index(['booking_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_status_history');
        Schema::dropIfExists('booking_travelers');
        Schema::dropIfExists('bookings');
    }
};
