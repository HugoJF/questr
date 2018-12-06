<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_items', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('index');
			$table->string('market_hash_name');
			$table->string('item_name');
			$table->string('skin_name');
			$table->string('condition');
			$table->boolean('stattrak');
			$table->integer('price');
			$table->text('icon_url');
			$table->string('name_color')->nullable();
			$table->string('quality_color')->nullable();
			$table->string('rarity_color')->nullable();

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
		Schema::dropIfExists('shop_items');
	}
}
