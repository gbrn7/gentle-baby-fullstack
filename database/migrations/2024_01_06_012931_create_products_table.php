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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('hpp', 10 , 2 );
            $table->float('price', 10 , 2 );
            $table->string('size_volume');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_cashback');
            $table->float('cashback_value', 10,2);
            $table->enum('status', ['active', 'inactive']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
