<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonalPricingTable extends Migration
{
    public function up()
    {
        Schema::create('seasonal_pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trailer_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('trailer_id')->references('id')->on('trailers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seasonal_pricings');
    }
}

