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
	Route::get('/', 'QuestController@index')->name('index')->middleware('can:view,App\Quest');
	Route::post('/', 'QuestController@store')->name('store')->middleware('can:create,App\Quest');
	Route::get('create', 'QuestController@create')->name('create')->middleware('can:create,App\Quest');
	Route::get('{quest}', 'QuestController@show')->name('show')->middleware('can:view,quest');
	Route::get('{quest}/start', 'QuestController@start')->name('start')->middleware('can:play,quest');
	Route::get('{quest}/finish', 'QuestController@finish')->name('finish')->middleware('can:play,quest');

	Route::get('{quest}/filters/create', 'QuestFilterController@create')->name('filters.create')->middleware('can:create,App\QuestFilter');
	Route::post('{quest}/filters', 'QuestFilterController@store')->name('filters.store')->middleware('can:create,App\QuestFilter');
});

Route::prefix('quest-filters')->name('quest-filters.')->group(function () {
	Route::delete('{questfilter}/', 'QuestFilterController@delete')->name('delete')->middleware('can:delete,questfilter');
});

Route::prefix('shop')->name('shop.')->group(function () {
	Route::get('/', 'ShopController@index')->name('index')->middleware('can:view,App\ShopItem');
	Route::get('{shopitem}', 'ShopController@show')->name('show')->middleware('can:view,App\ShopItem');
	Route::post('{shopitem}', 'ShopController@buy')->name('buy')->middleware('can:buy,App\ShopItem');
});

Route::prefix('inventory')->name('inventory.')->group(function () {
	Route::get('/', 'InventoryController@index')->name('index')->middleware('can:view,App\Inventory');
	Route::get('equip/{inventory}', 'InventoryController@equip')->name('equip')->middleware('can:equip,inventory');
});

Route::prefix('coupon')->name('coupon.')->group(function () {
	Route::get('create', 'CouponController@create')->name('create')->middleware('can:create,App\Coupon');

	Route::post('/', 'CouponController@store')->name('store')->middleware('can:create,App\Coupon');
	Route::post('use', 'CouponController@use')->name('use')->middleware('can:use,App\Coupon');
});

Route::get('is-admin', function () {
	return Auth::user()->isAdmin() ? 'true' : 'false';
});