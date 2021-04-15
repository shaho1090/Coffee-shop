<?php

namespace Tests\Feature;

use App\Models\OrderHeader;
use App\Models\OrderStatus;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $productVariantA = ProductVariant::factory()->create();
        $productVariantB = ProductVariant::factory()->create();

        $orderData = [
            'order_data' => [
                [
                    'product_variant_id' => $productVariantA->id,
                    'quantity' => 1,
                ],
                [
                    'product_variant_id' => $productVariantB->id,
                    'quantity' => 4,
                ]
            ]
        ];

        $this->postJson(route('order.store'), $orderData);

        $this->assertDatabaseHas('order_headers', [
            'user_id' => $customer->id,
            'status_id' => OrderStatus::waiting()->id
        ]);

        $this->assertDatabaseHas('order_lines', [
            'product_variant_id' => $orderData['order_data'][0]['product_variant_id'],
            'quantity' => $orderData['order_data'][0]['quantity'],
        ]);

        $this->assertDatabaseHas('order_lines', [
            'product_variant_id' => $orderData['order_data'][1]['product_variant_id'],
            'quantity' => $orderData['order_data'][1]['quantity'],
        ]);
    }

    public function test_a_customer_can_see_its_order()
    {
        $this->withoutExceptionHandling();
        $customer = User::factory()->create();

        $customer->grantRole(Role::customer()->first());

        Sanctum::actingAs($customer);

        $order = OrderHeader::factory()->hasLines(2)->create([
            'user_id' => $customer->id,
        ]);

        $this->getJson(route('order.index'))
            ->assertJsonFragment([
                "id" => $customer->id,
                "name" => $customer->name,
                "email" => $customer->email,
            ])->assertJsonFragment([
                "id" => $order->first()->id,
                "status" => "waiting",
                "total_price" => $order->total_price,
            ]);
    }

    public function test_the_a_customer_can_update_its_order_when_it_status_is_waiting()
    {
        $this->withoutExceptionHandling();
        $customer = User::factory()->create();

        $customer->grantRole(Role::customer()->first());

        Sanctum::actingAs($customer);

        $order = OrderHeader::factory()->hasLines(2)->create([
            'user_id' => $customer->id,
        ]);

        $productVariantA = ProductVariant::factory()->create();
        $productVariantB = ProductVariant::factory()->create();

        $orderUpdateData = [
            'order_data' => [
                [
                    'line_id' => $order->lines()->first()->id,
                    'product_variant_id' => $productVariantA->id,
                    'quantity' => 4,
                ],
                [
                    'line_id' => $order->lines()->get()->last()->id,
                    'product_variant_id' => $productVariantB->id,
                    'quantity' => 5,
                ]
            ]
        ];

        $this->patchJson(route('order.update', $order), $orderUpdateData);

        $this->assertDatabaseHas('order_lines', [
            'header_id' => $order->id,
            'id' => $orderUpdateData['order_data'][0]['line_id'],
            'product_variant_id' => $orderUpdateData['order_data'][0]['product_variant_id'],
            'quantity' => $orderUpdateData['order_data'][0]['quantity']
        ]);

        $this->assertDatabaseHas('order_lines', [
            'header_id' => $order->id,
            'id' => $orderUpdateData['order_data'][1]['line_id'],
            'product_variant_id' => $orderUpdateData['order_data'][1]['product_variant_id'],
            'quantity' => $orderUpdateData['order_data'][1]['quantity']
        ]);
    }
}
