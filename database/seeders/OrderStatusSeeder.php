<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('order_statuses')->insert([
           [
               'title' => 'waiting',
           ],
           [
               'title' => 'preparation',
           ],
           [
               'title' => 'ready',
           ],
           [
               'title' => 'delivered'
           ]
       ]);

    }
}
