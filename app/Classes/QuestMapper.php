<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/1/2018
 * Time: 8:05 AM
 */

namespace App\Classes;


use App\Quest;

class QuestMapper
{
	private static $map = [
		'KILL_COUNT' => QuestKillCount::class,
	];

	private static $filters = [
		'KILL_COUNT' => [
			'weapon' => [
				'ak47', 'awp',
			],
		],
	];

	static function getRunnerBase($type)
	{
		$class = static::getRunnerClass($type);

		$runner = new $class();

		return $runner;
	}

	static function getRunnerClass($type)
	{
		return static::$map[ $type ];
	}

	static function getTypes()
	{
		return array_keys(static::$map);
	}

	static function getFilterKeys($type)
	{
		if (array_key_exists($type, static::$filters)) {
			$filters = static::$filters[ $type ];
			$keys = array_keys($filters);

			return $keys;
		} else {
			return [];
		}
	}

	static function getFilterValues($type, $key)
	{
		if (array_key_exists($type, static::$filters) && array_key_exists($key, static::$filters[ $type ])) {
			$filters = static::$filters[ $type ];
			$values = array_values($filters[ $key ]);

			return $values;
		} else {
			return [];
		}
	}
}