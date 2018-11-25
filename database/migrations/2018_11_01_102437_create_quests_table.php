<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quests', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->text('description');

            $table->boolean('hidden')->default(false);
            $table->string('code')->nullable();

            $table->string('type');
            $table->integer('cost');
            $table->integer('goal');
            $table->integer('reward');

            $table->dateTime('startAt');
            $table->dateTime('endAt');

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
        Schema::dropIfExists('quests');
    }
}
