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
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('total_price', 8, 2)->default(0.00)->change(); // Set a default value for total_price
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('total_price', 8, 2)->nullable()->change(); // Make total_price nullable again if needed
        });
    }

};
