<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class VendorFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_apply_as_vendor()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/vendor/apply', [
            'store_name' => 'Test Store',
            'phone' => '01911123456',
            'city' => 'Dhaka',
            'address' => 'Mirpur-10'
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.store_name', 'Test Store');
        
        $this->assertTrue($user->hasRole('vendor'));
    }
}
