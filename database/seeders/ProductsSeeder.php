<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('products')->insert([
            'uuid'  => Str::uuid(),
            'name'  => 'iPhone 10+',
            'type'  => 'electronic',
            'price' => 10000000,
            'quantity'  => 100,
            'created_at'    => date("Y-m-d H:i:s")
        ]);

        DB::table('products')->insert([
            'uuid'  => Str::uuid(),
            'name'  => 'Meja IKEA 1',
            'type'  => 'furniture',
            'price' => 1500000,
            'quantity'  => 50,
            'created_at'    => date("Y-m-d H:i:s")
        ]);

        DB::table('products')->insert([
            'uuid'  => Str::uuid(),
            'name'  => 'Headset Hyper Max 12',
            'type'  => 'accessories',
            'price' => 2000000,
            'quantity'  => 10,
            'created_at'    => date("Y-m-d H:i:s")
        ]);
    }
}
