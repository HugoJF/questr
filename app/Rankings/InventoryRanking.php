<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 1/3/2019
 * Time: 1:52 AM
 */

namespace App\Rankings;


use App\User;

class InventoryRanking extends BaseRanking
{

	private static $defaultAmount = 5;

	public static function getStub()
	{
		return 'inventory';
	}

	public static function getTitle()
	{
		return 'Inventory ranking';
	}

	public static function getDescription()
	{
		return 'Ranking based on current inventory value';
	}

	public static function getView()
	{
		return 'rankings.inventory';
	}

	public static function getRanking($amount = null)
	{
		$users = User::all();

		$amount = $amount ?? static::$defaultAmount;

		$users = $users->sortByDesc(function ($user) {
			return $user->inventories()->count();
		});

		return $users->values()->take($amount);
	}
}