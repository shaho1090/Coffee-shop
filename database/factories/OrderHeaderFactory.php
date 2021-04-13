<?php

namespace Database\Factories;

use App\Models\OrderHeader;
use App\Models\OrderStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderHeaderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderHeader::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => Carbon::now()->toDateTimeString(),
            'user_id' => User::factory(),
            'status_id' => OrderStatus::waiting()->id,
        ];
    }

    public function waiting()
    {
        return $this->state(function () {
            return [
                'status_id' => OrderStatus::waiting()->id,
            ];
        });
    }

    public function preparation()
    {
        return $this->state(function () {
            return [
                'status_id' => OrderStatus::preparation()->id,
            ];
        });
    }

    public function ready()
    {
        return $this->state(function () {
            return [
                'status_id' => OrderStatus::ready()->id,
            ];
        });
    }

    public function delivered()
    {
        return $this->state(function () {
            return [
                'status_id' => OrderStatus::delivered()->id,
            ];
        });
    }
}
