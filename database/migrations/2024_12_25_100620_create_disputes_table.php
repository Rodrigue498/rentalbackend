<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

  public function up()
{
    Schema::create('disputes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // User ID for whom the dispute is raised
        $table->text('description'); // Description of the dispute
        $table->boolean('resolved')->default(false); // Whether the dispute is resolved
        $table->text('resolution')->nullable(); // The resolution text by admin
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
