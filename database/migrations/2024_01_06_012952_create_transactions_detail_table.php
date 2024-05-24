<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('invoice_id')->nullable()->constrained('invoice');
            $table->float('hpp', 10, 2);
            $table->float('price', 10, 2);
            $table->enum('process_status', ['unprocessed ', 'processing', 'processed', 'taken', 'cancel'])->default('unprocessed');
            $table->boolean('invoice_status')->default(0);
            $table->integer('qty');
            $table->boolean('is_cashback');
            $table->float('cashback_value', 10, 2);
            $table->integer('qty_cashback_item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_detail');
    }
};
