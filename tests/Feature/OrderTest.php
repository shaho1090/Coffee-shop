<?php

namespace Tests\Feature;

use App\Models\Option;
use App\Models\OrderStatus;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ManagerSeeder;
use Database\Seeders\OrderStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        (new DatabaseSeeder())->run();
    }

    public function test_a_customer_can_store_an_order()
    {
        $this->withoutExceptionHandling();
        $customer = User::factory()->create();

        $customer->grantRole(Role::customer()->first());

        Sanctum::actingAs($customer);

        $productVariant = ProductVariant::factory()->create();

        $orderData = [
            'product_variant_id' => $productVariant->id,
            'quantity' => 3,
        ];

        $this->postJson(route('order.store'),$orderData);

        $this->assertDatabaseHas('orders',[
            'user_id' => $customer->id,
            'product_variant_id' => $orderData['product_variant_id'],
            'quantity' => $orderData['quantity'],
            'status_id' => OrderStatus::waiting()->id
        ]);
    }
}
