<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plugin;

class PluginDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. bKash Payment Gateway
        Plugin::updateOrCreate(
            ['slug' => 'bkash-payment'],
            [
                'name' => 'bKash Payment Gateway',
                'version' => '1.0.0',
                'status' => 'active',
                'installed_at' => now(),
                'activated_at' => now(),
                'settings' => [
                    'username' => '01701164639',
                    'password' => 'bkashsub123',
                    'app_key' => '4f1cf3093f4c64ad01d3',
                    'app_secret' => '7dd6f264103a890731f786858976a26d',
                    'sandbox' => true
                ]
            ]
        );

        // 2. Flat Rate Shipping
        Plugin::updateOrCreate(
            ['slug' => 'flat-rate-shipping'],
            [
                'name' => 'Flat Rate Shipping',
                'version' => '1.0.0',
                'status' => 'active',
                'installed_at' => now(),
                'activated_at' => now(),
                'settings' => [
                    'default_rate' => 60,
                    'free_shipping_threshold' => 2000,
                    'zones' => [
                        'dhaka' => 60,
                        'chittagong' => 100,
                        'other' => 120
                    ]
                ]
            ]
        );

        // 3. Pathao Shipping
        Plugin::updateOrCreate(
            ['slug' => 'pathao'],
            [
                'name' => 'Pathao Courier',
                'version' => '1.1.0',
                'status' => 'active',
                'installed_at' => now(),
                'activated_at' => now(),
                'settings' => [
                    'client_id' => 'MOCK_CLIENT_ID',
                    'client_secret' => 'MOCK_CLIENT_SECRET',
                    'username' => 'mondals_demo',
                    'password' => 'pass123',
                    'sandbox' => true
                ]
            ]
        );
    }
}
