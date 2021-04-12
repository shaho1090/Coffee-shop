<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;

class SampleOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $milk = Option::create([
            'name' => 'Milk',
            'level' => 0,
        ]);

        $milk->addChild('skim');
        $milk->addChild('semi');
        $milk->addChild('whole');

        $size = Option::create([
            'name' => 'Size',
            'level' => 0,
        ]);

        $size->addChild('small');
        $size->addChild('medium');
        $size->addChild('large');

        $shots = Option::create([
            'name' => 'Shots',
            'level' => 0,
        ]);

        $shots->addChild('single');
        $shots->addChild('double');
        $shots->addChild('triple');

        $consumeLocation = Option::create([
            'name' => 'Shots',
            'level' => 0,
        ]);

        $consumeLocation->addChild('take-away');
        $consumeLocation->addChild('in-shop');

    }
}
