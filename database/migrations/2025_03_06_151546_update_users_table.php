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
        Schema::table('users', function (Blueprint $table) {
            $table->string('businessName')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->date('birthday')->nullable();
            $table->text('about')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip')->nullable();
            $table->string('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'businessName', 'firstName', 'lastName', 'birthday', 'about', 
                'address1', 'address2', 'city', 'state', 'country', 'zip', 'avatar'
            ]);
        });
    }
};
