<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourcesSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources_summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_block_id')->unsigned();
            $table->foreign('template_block_id')->references('id')->on('template_blocks')->onDelete('cascade');
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
        Schema::dropIfExists('sources_summaries');
    }
}
