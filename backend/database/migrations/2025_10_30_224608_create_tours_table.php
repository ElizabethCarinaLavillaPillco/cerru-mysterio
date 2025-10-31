<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description');
            $table->text('short_description')->nullable();
            
            $table->foreignId('category_id')->constrained('tour_categories');
            $table->foreignId('destination_id')->constrained('destinations');
            
            $table->integer('duration_days');
            $table->integer('duration_nights');
            
            $table->enum('difficulty_level', ['easy', 'moderate', 'challenging', 'difficult']);
            $table->integer('max_group_size')->default(15);
            $table->integer('min_group_size')->default(2);
            
            $table->decimal('base_price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('currency')->default('USD');
            
            $table->boolean('featured')->default(false);
            $table->integer('featured_order')->default(0);
            
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            
            // Estadísticas
            $table->integer('views_count')->default(0);
            $table->integer('bookings_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
            $table->index('category_id');
            $table->index('destination_id');
            $table->index('featured');
        });

        Schema::create('tour_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->enum('type', ['gallery', 'cover', 'thumbnail'])->default('gallery');
            $table->string('alt_text')->nullable();
            $table->boolean('is_cover')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['tour_id', 'type']);
        });

        Schema::create('tour_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->string('season')->default('regular'); // regular, high, low
            $table->decimal('adult_price', 10, 2);
            $table->decimal('child_price', 10, 2)->nullable();
            $table->decimal('infant_price', 10, 2)->nullable();
            $table->integer('min_group_for_discount')->nullable();
            $table->decimal('group_discount_percentage', 5, 2)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestamps();
        });

        Schema::create('tour_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->integer('day');
            $table->string('title');
            $table->text('description');
            $table->json('activities')->nullable();
            $table->string('accommodation')->nullable();
            $table->string('meals')->nullable();
            $table->timestamps();

            $table->index(['tour_id', 'day']);
        });

        Schema::create('tour_inclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['included', 'excluded']);
            $table->string('description');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_inclusions');
        Schema::dropIfExists('tour_itineraries');
        Schema::dropIfExists('tour_prices');
        Schema::dropIfExists('tour_images');
        Schema::dropIfExists('tours');
    }
};
