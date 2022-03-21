<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('roles')->insert([
            'user_id' => 1,
            'role' => 'admin'
        ]);

        DB::table('roles')->insert([
            'user_id' => 2,
            'role' => 'customer'
        ]);
    }
}
