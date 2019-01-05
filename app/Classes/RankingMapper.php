<?php

namespace App\Classes;

use App\Quest;
use App\Rankings\BalanceRanking;
use App\Rankings\BaseRanking;
use App\Rankings\InventoryRanking;

class RankingMapper
{
	/** @var BaseRanking[] */
	private static $map = [
		BalanceRanking::class,
		InventoryRanking::class,
	];

	public static function getRankings()
	{

		return static::$map;
	}

	public static function getRankingTypes()
	{
		$types = collect(static::$map)->map(function ($rank) {
			return $rank->getStub();
		});

		return $types->toArray();
	}

	/**
	 * @param $stub - ranking stub
	 *
	 * @return BaseRanking
	 * @throws \Exception
	 */
	public static function getRunnerByStub($stub)
	{
		$filtered = collect(static::$map)->reject(function ($runner) use ($stub) {
			return $runner::getStub() != $stub;
		});

		if ($filtered->count() == 1) {
			return $filtered->first();
		} else {
			throw new \Exception("Multiple runners found with stub $stub");
		}
	}
}