<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('quizzes')) {
            Schema::create('quizzes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('course_id')->unsigned();
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
                $table->integer('subcourse_id')->unsigned()->nullable();
                $table->foreign('subcourse_id')->references('id')->on('courses')->onDelete('cascade');
                $table->string('quiz_title', 255)->nullable();
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
        Schema::dropIfExists('quizzes');
    }
}
