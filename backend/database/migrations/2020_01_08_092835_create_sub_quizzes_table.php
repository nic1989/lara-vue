<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sub_quizzes')) {
            Schema::create('sub_quizzes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('course_id')->unsigned();
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
                $table->string('title', 255);
                $table->string('video_name', 255);
                $table->integer('status')->default(1);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_quizzes');
    }
}
