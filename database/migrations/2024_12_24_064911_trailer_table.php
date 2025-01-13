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
Schema::create('trailers', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id'); // Foreign key for the owner
    $table->string('title');
    $table->text('description');
    $table->string('type');
    $table->string('features');
    $table->float('size');
    $table->integer('capacity');
    $table->boolean('available')->default(true); // Availability status
    $table->decimal('price', 8, 2); // Pricing
    $table->json('images')->nullable(); // Image paths
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
