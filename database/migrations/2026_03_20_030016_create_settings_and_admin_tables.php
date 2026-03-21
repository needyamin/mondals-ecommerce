<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Settings (key-value store for all system settings)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general')->comment('e.g. general, payment, shipping, seo, email');
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('text')->comment('text, textarea, boolean, number, json, image');
            $table->boolean('is_public')->default(false)->comment('Whether exposed via API');
            $table->timestamps();

            $table->unique(['group', 'key']);
        });

        // Currencies
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('name');
            $table->string('symbol', 10);
            $table->decimal('exchange_rate', 12, 6)->default(1.000000);
            $table->enum('position', ['before', 'after'])->default('before');
            $table->integer('decimal_places')->default(2);
            $table->string('decimal_separator', 5)->default('.');
            $table->string('thousand_separator', 5)->default(',');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Notification Templates
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->comment('e.g. email, sms, push');
            $table->string('subject')->nullable();
            $table->longText('body');
            $table->json('variables')->nullable()->comment('Available template variables');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('user_id');
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('settings');
    }
};
