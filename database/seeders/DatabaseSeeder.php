<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name'=>'superAdmin',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        Role::create([
            'name'=>'admin',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        Role::create([
            'name'=>'waiter',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
         User::factory(1)->create();

    }
}
