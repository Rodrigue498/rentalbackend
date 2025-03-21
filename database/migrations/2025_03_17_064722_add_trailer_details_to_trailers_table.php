<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('trailers', function (Blueprint $table) {
            $table->string('connector_type')->nullable();
            $table->boolean('trailer_brakes')->default(false);
            $table->string('hitch_ball_size')->nullable();
            $table->decimal('trailer_weight', 8, 2)->nullable();
            $table->decimal('max_payload', 8, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('trailers', function (Blueprint $table) {
            $table->dropColumn([
                'connector_type',
                'trailer_brakes',
                'hitch_ball_size',
                'trailer_weight',
                'max_payload'
            ]);
        });
    }
};

