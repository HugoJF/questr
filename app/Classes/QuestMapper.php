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

	private static $defaultFilters = [
		'server' => [ '*' ],
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

	static function getFilter($type)
	{
		if (array_key_exists($type, static::$map)) {
			$class = static::$map[ $type ];

			$result = call_user_func($class . '::getQuestFilters');

			return array_merge($result, static::$defaultFilters);
		}

		return [];
	}

	static function getFilterKeys($type)
	{
		$data = static::getFilter($type);

		if ($data) {
			return array_keys($data);
		}

		return [];
	}

	static function getFilterValues($type, $key)
	{
		$data = static::getFilter($type);

		if ($data && array_key_exists($key, $data)) {
			return $data[ $key ];
		}

		return [];
	}
}