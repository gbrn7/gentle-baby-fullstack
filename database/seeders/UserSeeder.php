<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
            'name' => 'Farid Angga',
            'email' => 'faridangga12@gmail.com',
            'password' => 'superadmin',
            'role' => 'super_admin',
            'phone_number' => '085469875236',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'name' => 'Farhan',
            'email' => 'farhan10@gmail.com',
            'password' => 'admin',
            'role' => 'admin',
            'phone_number' => '08846987968',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'name' => 'Dani Aditya',
            'email' => 'daniadit@gmail.com',
            'password' => 'superadmincust',
            'role' => 'super_admin_cust',
            'phone_number' => '085469875236',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'name' => 'Dodit',
            'email' => 'Dodit@gmail.com',
            'password' => 'admincust',
            'role' => 'admin_cust',
            'phone_number' => '085698745214',
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
        }   
}
