<?php

namespace Tests\Feature;

use App\Models\Option;
use App\Models\User;
use Database\Seeders\ManagerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        (new ManagerSeeder())->run();
    }

    public function test_the_manager_can_create_a_product()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(User::where('email', 'manager_one@gmail.com')->first());

        $optionA = Option::factory()->child()->create();
        $optionB = Option::factory()->child()->create();

        $productData = [
            'name' => 'product one',
            'variants' => [
                [
                    'option_id' => $optionA->id,
                    'price' => 24,
                ],
                [
                    'option_id' => $optionB->id,
                    'price' => 18,
                ]
            ]
        ];

        $this->postJson(route('product.store'),$productData)->dump();

        $this->assertDatabaseHas('products',[
            'name' => $productData['name']
        ]);

        $this->assertDatabaseHas('product_variants',[
            'option_id' => $productData['variants'][0]['option_id'],
            'price' => $productData['variants'][0]['price']
        ])->assertDatabaseHas('product_variants',[
            'option_id' => $productData['variants'][1]['option_id'],
            'price' => $productData['variants'][1]['price']
        ]);
    }
}
