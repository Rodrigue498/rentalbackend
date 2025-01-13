<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade'); // Reference to bookings table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');    // Reference to users table
            $table->string('transaction_id')->nullable(); // Payment gateway transaction ID
            $table->decimal('amount', 10, 2);            // Total amount paid by the renter
            $table->decimal('service_fee', 10, 2);       // Service fee added to the renter's amount
            $table->decimal('owner_payout', 10, 2);      // Final payout for the owner after deducting 15%
            $table->string('status')->default('pending'); // Payment status (e.g., pending, paid, failed)
            $table->string('payment_method')->nullable(); // Payment method (e.g., Stripe, PayPal)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

