<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['group' => 'storage', 'key' => 'product_upload_disk'],
            [
                'value' => 'public',
                'type' => 'text',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('settings')->where('group', 'storage')->where('key', 'product_upload_disk')->delete();
    }
};
