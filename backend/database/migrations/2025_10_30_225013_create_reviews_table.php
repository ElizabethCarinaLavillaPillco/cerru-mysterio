<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('tour_id')->constrained();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            
            $table->integer('rating'); // 1-5
            $table->string('title')->nullable();
            $table->text('comment');
            
            $table->boolean('approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tour_id', 'approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
