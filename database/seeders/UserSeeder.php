<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

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
            'email' => 'superadmin@gmail.com',
            'password' => Crypt::encryptString('superadmin'),
            'role' => 'super_admin',
            'phone_number' => '082132679938',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'name' => 'Farhan',
            'email' => 'admin@gmail.com',
            'password' => Crypt::encryptString('admin'),
            'role' => 'admin',
            'phone_number' => '08846987968',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'name' => 'Muhammad Rayhan Gibran',
            'email' => 'rayhan.gibran19@gmail.com',
            'password' => Crypt::encryptString('superadmincust'),
            'role' => 'super_admin_cust',
            'phone_number' => '082132679938',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'name' => 'Dodit',
            'email' => 'Dodit@gmail.com',
            'password' => Crypt::encryptString('admincust'),
            'role' => 'admin_cust',
            'phone_number' => '085698745214',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
                'name' => 'fiki',
                'email' => 'fiki@gmail.com',
                'password' => Crypt::encryptString('superadmincust'),
                'role' => 'super_admin_cust',
                'phone_number' => 'sdsdd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        }   
}
