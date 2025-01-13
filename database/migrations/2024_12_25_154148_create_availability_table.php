<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvailabilityTable extends Migration
{
    public function up()
    {
        Schema::create('availability', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trailer_id');
            $table->date('date');
            $table->boolean('available')->default(true);
            $table->timestamps();

            $table->foreign('trailer_id')->references('id')->on('trailers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('availability');
    }
}
