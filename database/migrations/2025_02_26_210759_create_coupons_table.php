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
        Schema::create('coupons', function (Blueprint $table) {
            $table->string('name')->primary();
            $table->double('percentage_discount'); // You can change the type if needed
            $table->timestamps(); // Add created_at and updated_at columns
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
