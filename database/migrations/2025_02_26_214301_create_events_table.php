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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 1500);
            $table->integer('available_tickets');
            $table->string('longitude');
            $table->string('latitude');
            $table->enum('category', \App\Category::values());
            $table->foreignId('creator_id')->constrained('users'); // Foreign key for the creator (User)
            $table->date('date_start');
            $table->date('date_finish');
            $table->time('time_start');
            $table->time('time_finish');
            $table->string('meeting_url');
            $table->string('type');
            $table->double('price');
            $table->timestamps();
        });

        Schema::create('event_tag', function (Blueprint $table) {
            $table->foreignId('event_id')->constrained('events');
            $table->string('tag_name'); // Use 'tag_name' instead of 'tag_id'
            $table->foreign('tag_name')->references('name')->on('tags'); // Reference 'name' in the 'tags' table
            $table->primary(['event_id', 'tag_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_tag');

    }
};
