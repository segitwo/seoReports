<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHideIfReduceToAveragePositionsBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('average_positions_blocks', function (Blueprint $table) {
            $table->boolean('hide_if_reduce')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('average_positions_blocks', function (Blueprint $table){
            $table->dropColumn('hide_if_reduce');
        });
    }
}
