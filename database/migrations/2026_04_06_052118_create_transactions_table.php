<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('payment', 10, 2);
            $table->decimal('change_amount', 10, 2);
            $table->timestamps();
            $table->string('reference')->nullable();
            $table->string('card_type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};