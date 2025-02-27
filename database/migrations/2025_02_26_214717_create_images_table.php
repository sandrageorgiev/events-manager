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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('content_type');
            $table->binary('data'); // Use binary for storing byte arrays
            $table->foreignId('event_id')->constrained('events'); // Foreign key constraint to events table
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
