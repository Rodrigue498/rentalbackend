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
        Schema::create('unavailable_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trailer_id');
            $table->date('date');
            $table->timestamps();

            $table->foreign('trailer_id')->references('id')->on('trailers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('unavailable_dates');
    }
};
