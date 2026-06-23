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

            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('city');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('price', 10, 2);
            $table->integer('total_seats');
            $table->integer('available_seats');
            $table->string('banner_path');
            $table->string('noc_document_path');
            $table->string('approval_status')->default('draft');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
