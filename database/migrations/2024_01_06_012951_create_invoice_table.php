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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code');
            $table->foreignId('company_id')->constrained('company');
            $table->float('amount', 30, 2);
            $table->date('payment_due_date');
            $table->boolean('payment_status')->default(0);
            $table->float('dp_value', 30, 2)->default(0);
            $table->date('dp_due_date')->nullable();
            $table->boolean('dp_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
