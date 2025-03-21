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
        Schema::table('trailers', function (Blueprint $table) {
            if (!Schema::hasColumn('trailers', 'features')) {
                $table->json('features')->nullable()->after('type'); 
            }
    
            if (!Schema::hasColumn('trailers', 'trailer_weight')) {
                $table->decimal('trailer_weight', 8, 2)->after('size');
            }
    
            if (!Schema::hasColumn('trailers', 'max_payload')) {
                $table->decimal('max_payload', 8, 2)->after('trailer_weight');
            }
    
            if (!Schema::hasColumn('trailers', 'connector_type')) {
                $table->string('connector_type')->after('max_payload');
            }
    
            if (!Schema::hasColumn('trailers', 'trailer_brakes')) {
                $table->string('trailer_brakes')->after('connector_type');
            }
    
            if (!Schema::hasColumn('trailers', 'hitch_ball_size')) {
                $table->string('hitch_ball_size')->after('trailer_brakes');
            }
    
            if (!Schema::hasColumn('trailers', 'location')) {
                $table->string('location')->after('price');
            }
        });
    }
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trailers', function (Blueprint $table) {
            // Revert changes
            $table->string('features')->change(); // Change back to string
            $table->float('size')->change(); // Change back to float

            // Drop newly added columns
            $table->dropColumn([
                'trailer_weight',
                'max_payload',
                'connector_type',
                'trailer_brakes',
                'hitch_ball_size',
                'location'
            ]);
        });
    }
};
