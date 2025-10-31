<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['card', 'bank_transfer', 'paypal', 'stripe', 'cash', 'other']);
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('transaction_id')->unique()->nullable();
            
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            
            $table->enum('status', [
                'pending',
                'processing',
                'completed',
                'failed',
                'refunded',
                'cancelled'
            ])->default('pending');
            
            $table->string('payment_gateway')->nullable();
            $table->json('gateway_response')->nullable();
            
            $table->text('notes')->nullable();
            $table->text('receipt_url')->nullable();
            
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['booking_id', 'status']);
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_methods');
    }
};
