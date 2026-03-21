<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('compare_price', 12, 2)->nullable()->comment('Original/strike-through price');
            $table->decimal('cost_price', 12, 2)->nullable()->comment('Purchase/cost price');
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->string('thumbnail')->nullable();
            $table->decimal('weight', 8, 2)->nullable()->comment('Weight in grams');
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_digital')->default(false);
            $table->boolean('is_taxable')->default(true);
            $table->boolean('track_quantity')->default(true);
            $table->boolean('allow_backorder')->default(false);
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->enum('visibility', ['public', 'hidden', 'catalog_only', 'search_only'])->default('public');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('sales_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index(['price', 'compare_price']);
            if (\Illuminate\Support\Facades\DB::connection()->getDriverName() !== 'sqlite') {
                $table->fullText(['name', 'short_description']);
            }
        });

        // Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('image');
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Product-Category pivot (a product can belong to multiple categories)
        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->primary(['category_id', 'product_id']);
        });

        // Product-Attribute pivot (which attributes a product uses)
        Schema::create('attribute_product', function (Blueprint $table) {
            $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->primary(['attribute_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_product');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
    }
};
