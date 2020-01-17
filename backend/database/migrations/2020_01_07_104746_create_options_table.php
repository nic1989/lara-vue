<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('options')) {
            Schema::create('options', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('question_id')->unsigned();
                $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
                $table->string('option_val', 255)->nullable();
                $table->integer('is_correct')->default(0);
                $table->text('answer')->nullable();
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
        Schema::dropIfExists('options');
    }
}
