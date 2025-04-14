<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('trailers', function (Blueprint $table) {
            // Add new column
            $table->string('location')->nullable(); 

            // Modify an existing column (change 'size' to decimal for precision)
            $table->decimal('size', 5, 2)->change(); 

            // Rename a column (e.g., 'capacity' to 'max_payload')
            $table->renameColumn('capacity', 'max_payload');

            // Drop a column
            $table->dropColumn('features'); 
        });
    }

    public function down()
    {
        Schema::table('trailers', function (Blueprint $table) {
            // Reverse the changes (for rollback)
            $table->dropColumn('location');
            $table->float('size')->change();
            $table->renameColumn('max_payload', 'capacity');
            $table->json('features');
        });
    }
};
