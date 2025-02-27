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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->double('price');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade'); // Foreign key referencing events
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade'); // Foreign key referencing users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
