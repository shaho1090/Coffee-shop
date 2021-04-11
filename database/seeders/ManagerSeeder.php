<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'manager_one',
            'email' => 'manager_one@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $user->roles()->attach(Role::manager()->first());
    }
}
