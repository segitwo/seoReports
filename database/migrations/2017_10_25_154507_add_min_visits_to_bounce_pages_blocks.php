<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinVisitsToBouncePagesBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bounce_pages_blocks', function (Blueprint $table){
            $table->integer('min_visits')->nullable()->unsigned();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bounce_pages_blocks', function (Blueprint $table){
            $table->dropColumn('min_visits');
        });
    }
}
