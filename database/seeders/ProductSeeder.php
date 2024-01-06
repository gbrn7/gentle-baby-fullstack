<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                "name" => "Amoxan",
                "hpp" => 10000,
                "price" => 12500,
                "size_volume" => 300,
                "thumbnail" => "",
                "is_cashback" => 1,
                "cashback_value" => 500,
                "status" => "active",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "name" => "Cetrizin",
                "hpp" => 12000,
                "price" => 15000,
                "size_volume" => 400,
                "thumbnail" => "",
                "is_cashback" => 0,
                "cashback_value" => 1000,
                "status" => "active",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
