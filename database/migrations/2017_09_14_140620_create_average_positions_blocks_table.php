<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAveragePositionsBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('average_positions_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_block_id')->unsigned();
            $table->foreign('template_block_id')->references('id')->on('template_blocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('average_positions_blocks');
    }
}
