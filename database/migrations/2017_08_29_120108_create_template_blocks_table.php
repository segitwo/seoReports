<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('comment_before')->nullable();
            $table->longText('comment_after')->nullable();;
            $table->integer('sortIndex')->default(0);
            $table->string('class_key')->nullable();
            $table->integer('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_blocks');
    }
}
