<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_to_cart()
    {
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id, 'price' => 500, 'quantity' => 10]);
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/cart', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.cart.items.0.product_id', $product->id);
    }
}
