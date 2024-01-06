<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transactions')->insert([
            [
                "company_id" => 2,
                "jatuh_tempo_dp" => null,
                "jatuh_tempo" => now(),
                "process_status" => "unprocessed",
                "payment_status" => 0,
                "dp_status" => 0,
                "transaction_complete_date" => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "company_id" => 2,
                "jatuh_tempo_dp" => date_add(now(), date_interval_create_from_date_string('1 days')),
                "jatuh_tempo" => date_add(now(), date_interval_create_from_date_string('6 weeks')),
                "process_status" => "processed",
                "payment_status" => 1,
                "dp_status" => 1,
                "transaction_complete_date" => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
