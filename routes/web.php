<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('home', [
		'randomQuests' => \App\Quest::inRandomOrder()->limit(3)->get(),
	]);
})->name('home');

Route::get('auth/steam', 'AuthController@redirectToSteam')->name('auth.steam');
Route::get('auth/steam/handle', 'AuthController@handle')->name('auth.steam.handle');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::prefix('quests')->name('quests.')->group(function () {
	Route::get('/', 'QuestController@index')->name('index');
	Route::post('/', 'QuestController@store')->name('store');
	Route::get('create', 'QuestController@create')->name('create');
	Route::get('{quest}', 'QuestController@show')->name('show');
	Route::get('{quest}/start', 'QuestController@start')->name('start');
	Route::get('{quest}/finish', 'QuestController@finish')->name('finish');

	Route::get('{quest}/filters/create', 'QuestFilterController@create')->name('filters.create');
	Route::post('{quest}/filters', 'QuestFilterController@store')->name('filters.store');
});

Route::prefix('quest-filters')->name('quest-filters.')->group(function () {
	Route::delete('{questfilter}/', 'QuestFilterController@delete')->name('delete');
});

Route::prefix('shop')->name('shop.')->group(function () {
	Route::get('/', 'ShopController@index')->name('index');
	Route::get('{shopitem}', 'ShopController@show')->name('show');
	Route::post('{shopitem}', 'ShopController@buy')->name('buy');
});

Route::prefix('inventory')->name('inventory.')->group(function () {
	Route::get('/', 'InventoryController@index')->name('index');
	Route::get('equip/{inventory}', 'InventoryController@equip')->name('equip');
});