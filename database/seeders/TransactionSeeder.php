<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transactions')->insert([
            [

                "transaction_code" => Str::random(10),
                "company_id" => 2,
                "created_at" => Carbon::now()->subDay(),
            ],
            [
                "transaction_code" => Str::random(10),
                "company_id" => 2,
                "created_at" => now()
            ],

        ]);
    }
}
