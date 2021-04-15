<?php

namespace Tests\Feature;

use App\Models\Option;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\ManagerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->postJson(route('product.store'), $productData);

        $this->assertDatabaseHas('products', [
            'name' => $productData['name']
        ]);

        $this->assertDatabaseHas('product_variants', [
            'option_id' => $productData['variants'][0]['option_id'],
            'price' => $productData['variants'][0]['price']
        ])->assertDatabaseHas('product_variants', [
            'option_id' => $productData['variants'][1]['option_id'],
            'price' => $productData['variants'][1]['price']
        ]);
    }

    public function test_the_customer_can_see_a_product()
    {
        $customer = User::factory()->create();

        $customer->grantRole(Role::customer()->first());

        Sanctum::actingAs($customer);

        $productVariant = ProductVariant::factory()->create();

        $product = $productVariant->product;

        $this->getJson(route('product.show', $product))//->dump();
        ->assertJsonFragment([
            "id" => $product->id,
            "name" => $product->name,
        ])->assertJsonFragment([
            "id" => $product->variants()->first()->option->id,
            "name" => $product->variants()->first()->option->name,
        ]);
    }

    public function test_a_manager_can_see_a_product()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(User::where('email', 'manager_one@gmail.com')->first());

        $product = ProductVariant::factory()->create()->product;

        $this->getJson(route('product.show', $product))
            ->assertJsonFragment([
                "id" => $product->id,
                "name" => $product->name,
            ])->assertJsonFragment([
                "id" => $product->variants()->first()->option->id,
                "name" => $product->variants()->first()->option->name,
            ]);
    }

    public function test_a_costumer_can_see_all_products()
    {
        $customer = User::factory()->create();

        $customer->grantRole(Role::customer()->first());

        Sanctum::actingAs($customer);

        $productA = ProductVariant::factory()->create()->product;
        $productB = ProductVariant::factory()->create()->product;

        $this->getJson(route('product.index'))//->dump();
        ->assertJsonFragment([
            "id" => $productA->id,
            "name" => $productA->name,
        ])->assertJsonFragment([
            "id" => $productB->id,
            "name" => $productB->name,
        ])->assertJsonFragment([
            "id" => $productA->variants()->first()->option->id,
            "name" => $productA->variants()->first()->option->name,
        ])->assertJsonFragment([
            "id" => $productB->variants()->first()->option->id,
            "name" => $productB->variants()->first()->option->name,
        ]);
    }
}
