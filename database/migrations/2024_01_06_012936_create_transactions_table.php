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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company');
            $table->date('jatuh_tempo_dp')->nullable();
            $table->date('jatuh_tempo');
            $table->enum('process_status', ['unprocessed ', 'processing', 'processed', 'taken']);
            $table->boolean('payment_status')->default(0);
            $table->float('dp_value', 10, 2)->default(0);
            $table->boolean('dp_status')->default(0);
            $table->date('transaction_complete_date')->nullable();
            $table->string('payment_receipt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
