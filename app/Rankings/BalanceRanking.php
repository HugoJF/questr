<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 1/3/2019
 * Time: 1:52 AM
 */

namespace App\Rankings;


use App\User;

class BalanceRanking extends BaseRanking
{

	private static $defaultAmount = 5;

	public static function getStub()
	{
		return 'balance';
	}

	public static function getTitle()
	{
		return 'Balance ranking';
	}

	public static function getDescription()
	{
		return 'Ranking based on current values';
	}

	public static function getView()
	{
		return 'rankings.balance';
	}

	public static function getRanking($amount = null)
	{
		$users = User::all();

		$amount = $amount ?? static::$defaultAmount;

		$users = $users->sortByDesc(function ($user) {
			return $user->balance;
		});

		return $users->values()->take($amount);
	}
}