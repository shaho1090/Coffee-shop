<?php

namespace Database\Factories;

use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderLineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderLine::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'header_id' => OrderHeader::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'quantity' => rand(1,100),
        ];
    }
}
