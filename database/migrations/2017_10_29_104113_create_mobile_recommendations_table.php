<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_recommendations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('start_month')->unsigned()->default(1);
            $table->integer('template_block_id')->unsigned();
            $table->foreign('template_block_id')->references('id')->on('template_blocks')->onDelete('cascade');

        });

        DB::table('class_maps')->insert(
            [
                [
                    'name' => 'MobileRecommendation',
                    'parent_class' => 'TemplateBlock'
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_recommendations');

        DB::table('class_maps')->where('name', 'MobileRecommendation')->delete();
    }
}
