<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transactions_detail')->insert([
            [
                "transaction_id" => 1,
                "invoice_id" => 1,
                "product_id" => 1,
                "hpp" => 10000,
                "price" => 12500,
                "qty" => 350,
                "is_cashback" => 1,
                "cashback_value" => 500,
                "qty_cashback_item" => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "transaction_id" => 1,
                "invoice_id" => 1,
                "product_id" => 2,
                "hpp" => 12000,
                "price" => 15000,
                "qty" => 200,
                "is_cashback" => 0,
                "cashback_value" => 500,
                "qty_cashback_item" => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "transaction_id" => 2,
                "invoice_id" => 1,
                "product_id" => 1,
                "hpp" => 10000,
                "price" => 12500,
                "qty" => 200,
                "is_cashback" => 1,
                "cashback_value" => 1000,
                "qty_cashback_item" => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "transaction_id" => 2,
                "invoice_id" => 1,
                "product_id" => 2,
                "hpp" => 12000,
                "price" => 15000,
                "qty" => 100,
                "is_cashback" => 0,
                "cashback_value" => 1000,
                "qty_cashback_item" => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "transaction_id" => 3,
                "invoice_id" => null,
                "product_id" => 1,
                "hpp" => 10000,
                "price" => 12500,
                "qty" => 240,
                "is_cashback" => 1,
                "cashback_value" => 1000,
                "qty_cashback_item" => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "transaction_id" => 3,
                "invoice_id" => null,
                "product_id" => 2,
                "hpp" => 12000,
                "price" => 15000,
                "qty" => 350,
                "is_cashback" => 0,
                "cashback_value" => 1000,
                "qty_cashback_item" => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
