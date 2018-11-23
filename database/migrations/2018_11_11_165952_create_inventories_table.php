<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('equipped');
            $table->boolean('synced');
            $table->integer('cost');
            $table->float('float', 8, 6);
            $table->unsignedInteger('user_id')->references('id')->on('users');
            $table->unsignedInteger('item_id')->references('id')->on('shop_items');

			$table->dateTime('ends_at')->nullable();

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
        Schema::dropIfExists('inventories');
    }
}
