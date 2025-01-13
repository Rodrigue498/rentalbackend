<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User who made the booking
            $table->unsignedBigInteger('trailer_id'); // Trailer being booked
            $table->date('start_date'); // Booking start date
            $table->date('end_date'); // Booking end date
            $table->string('status')->default('pending'); // Booking status (e.g., pending, confirmed, canceled)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('trailer_id')->references('id')->on('trailers')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
