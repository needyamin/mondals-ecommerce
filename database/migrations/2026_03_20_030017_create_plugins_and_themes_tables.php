<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Plugin Registry
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('version')->default('1.0.0');
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('author_url')->nullable();
            $table->string('path')->comment('Directory path of the plugin');
            $table->enum('status', ['active', 'inactive', 'error'])->default('inactive');
            $table->json('settings')->nullable();
            $table->json('dependencies')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamp('installed_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
        });

        // Plugin Hooks (event-driven extension points)
        Schema::create('plugin_hooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plugin_id')->constrained()->onDelete('cascade');
            $table->string('hook_name')->comment('e.g. after_checkout, before_order_create');
            $table->string('handler_class');
            $table->string('handler_method');
            $table->integer('priority')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('hook_name');
        });

        // Themes
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('version')->default('1.0.0');
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('screenshot')->nullable();
            $table->string('path');
            $table->boolean('is_active')->default(false);
            $table->json('settings')->nullable()->comment('Theme-specific customization settings');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
        Schema::dropIfExists('plugin_hooks');
        Schema::dropIfExists('plugins');
    }
};
