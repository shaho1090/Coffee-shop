<?php

namespace Tests\Feature;

use App\Models\Option;
use App\Models\Role;
use App\Models\User;
use Database\Factories\OptionFactory;
use Database\Seeders\ManagerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OptionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        (new ManagerSeeder())->run();
    }

    public function test_the_manager_can_create_new_parent_option()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(User::where('email', 'manager_one@gmail.com')->first());

        $optionData = [
            'name'=>'Milk',
            'parent_id' => null,
        ];

        $this->postJson(route('option.store'),$optionData);

        $this->assertDatabaseHas('options',[
            'name' => $optionData['name'],
            'parent_id' => $optionData['parent_id'],
            'level' => 0,
        ]);
    }

    public function test_the_manager_can_define_a_child_for_an_option()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(User::where('email', 'manager_one@gmail.com')->first());

        $parentOption = Option::factory()->create();

        $optionData = [
            'name'=>'Milk',
            'parent_id' => $parentOption->id,
        ];

        $this->postJson(route('option.store'),$optionData);

        $this->assertDatabaseHas('options',[
            'name' => $optionData['name'],
            'parent_id' => $optionData['parent_id'],
            'level' => 1,
        ]);
    }

    public function test_a_customer_can_not_create_an_option()
    {
        $customer = User::factory()->create();

        $customer->grantRole(Role::customer()->first());

        Sanctum::actingAs($customer);

        $optionData = [
            'name'=>'Milk',
            'parent_id' => null,
        ];

        $this->postJson(route('option.store'),$optionData)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);

        $this->assertDatabaseMissing('options',[
            'name' => $optionData['name'],
            'parent_id' => $optionData['parent_id'],
            'level' => 0,
        ]);
    }
}
