<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->string('provider')->nullable()->after('path')->comment('Main ServiceProvider class');
            $table->string('type')->nullable()->after('provider')->comment('Module type (payment, shipping, etc)');
        });
    }

    public function down(): void
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->dropColumn(['provider', 'type']);
        });
    }
};
