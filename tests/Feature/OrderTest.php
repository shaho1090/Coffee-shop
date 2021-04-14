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

        $this->assertDatabaseHas('order_lines', [
            'product_variant_id' => $orderData['order_data'][0]['product_variant_id'],
            'quantity' => $orderData['order_data'][0]['quantity'],
        ]);

        $this->assertDatabaseHas('order_lines', [
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

        $orders = OrderHeader::factory()->hasLines(2)->create([
            'user_id' => $customer->id,
        ]);

//        dd($orders->lines()->first()->productVariant()->first());

        $this->getJson(route('order.index'));
//            ->assertJsonFragment([
//                "id" => $customer->id,
//                "name" => $customer->name,
//                "email" => $customer->email,
//            ])->assertJsonFragment([
//                    "status" => "waiting",
//                    "total_price" => $orders->total_price,
//                    "lines" =>
//                        [
//                            [
//                                "id" => $orders->lines()->first()->id,
//                                "quantity" => $orders->lines()->first()->quantity,
//                                "product_variant" => [
//                                    "id" => $orders->lines()->first()->product_variant_id,
//                                    "product" => [
//                                        "id" => $orders->lines()->first()->productVariant->product->id,
//                                        "name" => "Dr. Icie Spinka III",
//                                        "created_at" => "2021-04-13T12 =>08 =>04.000000Z",
//                                        "updated_at" => "2021-04-13T12 =>08 =>04.000000Z",
//                                    ],
//                                    "option" => [
//                                        "id" => 16,
//                                        "name" => "Chase Morar",
//                                        "parent" => null,
//                                    ],
//                                    "price" => "43",
//                                ],
//                                "line_total_price" => 172,
//                            ],
//                            [
//                                "id" => 2,
//                                "quantity" => "94",
//                                "product_variant" => [
//                                    "id" => 2,
//                                    "product" => [
//                                        "id" => 2,
//                                        "name" => "Neoma Gleason DDS",
//                                        "created_at" => "2021-04-13T12 =>08 =>04.000000Z",
//                                        "updated_at" => "2021-04-13T12 =>08 =>04.000000Z",
//                                    ],
//                                    "option" => [
//                                        "id" => 17,
//                                        "name" => "Meaghan Moen",
//                                        "parent" => null,
//                                    ],
//                                    "price" => "55",
//                                    "line_total_price" => 5170,
//                                ]
//                            ]
//                        ]
//                        ]
//            ])->dump();
//            ->assertJsonFragment([
//                "quantity" => $orders->lines()->first()->quantity,
//                "product_variant" =>  $orders->lines()->first()->productVariant()->first()
//            ])->dump();


    }
}
