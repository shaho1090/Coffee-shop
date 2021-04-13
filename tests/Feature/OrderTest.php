<?php

namespace Tests\Feature;

use App\Http\Resources\OrderLineResource;
use App\Models\Option;
use App\Models\OrderHeader;
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

        $this->postJson(route('order.store'), $orderData)->dump();

        $this->assertDatabaseHas('order_headers', [
            'user_id' => $customer->id,
            'status_id' => OrderStatus::waiting()->id
        ]);

        $this->assertDatabaseHas('order_lines',[
            'product_variant_id' => $orderData['order_data'][0]['product_variant_id'],
            'quantity' => $orderData['order_data'][0]['quantity'],
        ]);

        $this->assertDatabaseHas('order_lines',[
            'product_variant_id' => $orderData['order_data'][1]['product_variant_id'],
            'quantity' => $orderData['order_data'][1]['quantity'],
        ]);
    }

    public function test_a_customer_can_see_its_orders()
    {
        $this->withoutExceptionHandling();
        $customer = User::factory()->create();

        $customer->grantRole(Role::customer()->first());

        Sanctum::actingAs($customer);

        $orders = OrderHeader::factory(2)->hasLines(3)->create([
            'user_id' => $customer->id,
        ]);

//        dd($orders->load('lines'));

        $this->getJson(route('order.index'))->dump();



    }
}
