<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_maps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('parent_class');
        });

        DB::table('class_maps')->insert(
            [
                [
                    'name' => 'TotalVisitsBlock',
                    'parent_class' => 'TemplateBlock'
                ],
                [
                    'name' => 'SourcesSummary',
                    'parent_class' => 'TemplateBlock'
                ],
                [
                    'name' => 'BouncePagesBlock',
                    'parent_class' => 'TemplateBlock'
                ],
                [
                    'name' => 'PopularPagesBlock',
                    'parent_class' => 'TemplateBlock'
                ],
                [
                    'name' => 'PositionsBlock',
                    'parent_class' => 'TemplateBlock'
                ],
                [
                    'name' => 'ConversionsBlock',
                    'parent_class' => 'TemplateBlock'
                ],
                [
                    'name' => 'AveragePositionsBlock',
                    'parent_class' => 'TemplateBlock'
                ],
                [
                    'name' => 'AutoCommentBlock',
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
        Schema::dropIfExists('class_maps');
    }
}
