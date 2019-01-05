<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/1/2018
 * Time: 7:41 AM
 */

namespace App\Classes;


class EventParser
{
	static $events = [
		PlayerDamageEvent::class,
	];

	public function parse($raw)
	{
		foreach (static::$events as $event) {
			if(($parsed = $event::build($raw))) {
				return $parsed;
			}
		}
	}
}