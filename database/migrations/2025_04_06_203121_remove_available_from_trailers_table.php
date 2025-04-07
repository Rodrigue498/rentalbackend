<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAvailableFromTrailersTable extends Migration
{
    public function up()
    {
        Schema::table('trailers', function (Blueprint $table) {
            $table->dropColumn('available');
        });
    }

    public function down()
    {
        Schema::table('trailers', function (Blueprint $table) {
            $table->boolean('available')->default(true);
        });
    }
}
