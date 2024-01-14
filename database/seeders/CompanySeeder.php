<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('company')->insert([
            [
            'name' => 'Baby Gentle',
            'address' => 'Malang',
            'email' => 'babygentleid@gmail.com',
            'phone_number' => '085698244',
            'owner_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'name' => 'CV Berkah Jaya',
            'address' => 'Malang',
            'email' => 'berkahjaya@gmail.com',
            'phone_number' => '78787845',
            'owner_id' => '3',
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
