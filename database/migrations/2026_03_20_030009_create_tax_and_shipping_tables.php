<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tax Rates
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->decimal('rate', 8, 4)->comment('Tax rate percentage');
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->boolean('is_compound')->default(false);
            $table->timestamps();
        });

        // Shipping Zones
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Shipping Zone Regions
        Schema::create('shipping_zone_regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_zone_id')->constrained()->onDelete('cascade');
            $table->string('country');
            $table->string('state')->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->timestamps();
        });

        // Shipping Methods
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_zone_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['flat_rate', 'free', 'weight_based', 'price_based'])->default('flat_rate');
            $table->decimal('cost', 12, 2)->default(0);
            $table->decimal('min_order_amount', 12, 2)->nullable()->comment('Min order for free shipping');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('shipping_zone_regions');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('tax_rates');
    }
};
