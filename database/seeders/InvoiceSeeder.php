<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('invoice')->insert([
            'invoice_code' => Str::random(10),
            "company_id" => 2,
            "amount" => 7375000,
            "payment_due_date" => now(),
            "payment_status" => 0,
            "dp_value" => 0,
            "dp_due_date" => now(),
            "dp_status" => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
