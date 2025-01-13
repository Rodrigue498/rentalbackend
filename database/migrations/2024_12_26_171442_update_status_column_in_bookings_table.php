<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->string('status')->default('pending')->change();
    });
}

public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->string('status', 255)->change(); // Revert changes if necessary
    });
}

};
