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
	// (\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}):(\d{1,5})\s-\s(\d{1,2}\/\d{1,2}\/\d{1,4})\s-\s(\d{1,2}:\d{1,2}:\d{1,2}):\s"(.+?)<(\d*)><(STEAM_\d:\d:\d+?)><(.+?)>"\s\[(-?\d+?)\s(-?\d+?)\s(-?\d+?)\]\sattacked\s"(.+?)<(\d+?)><(STEAM_\d:\d:\d+?)><(.+?)>"\s\[(-?\d+?)\s(-?\d+?)\s(-?\d+?)\]\swith\s"(.+?)"\s\(damage\s"(\d+?)"\)\s\(damage_armor\s"(\d+?)"\)\s\(health\s"(\d+?)"\)\s\(armor\s"(\d+?)"\)\s\(hitgroup\s"(.+?)"\)

	const PATTERN = "/(\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}):(\d{1,5})\s-\s(\d{1,2}\/\d{1,2}\/\d{1,4})\s-\s(\d{1,2}:\d{1,2}:\d{1,2}):\s\"(.+?)<(\d*)><(STEAM_\d:\d:\d+?)><(.+?)>\"\s\[(-?\d+?)\s(-?\d+?)\s(-?\d+?)\]\sattacked\s\"(.+?)<(\d+?)><(STEAM_\d:\d:\d+?)><(.+?)>\"\s\[(-?\d+?)\s(-?\d+?)\s(-?\d+?)\]\swith\s\"(.+?)\"\s\(damage\s\"(\d+?)\"\)\s\(damage_armor\s\"(\d+?)\"\)\s\(health\s\"(\d+?)\"\)\s\(armor\s\"(\d+?)\"\)\s\(hitgroup\s\"(.+?)\"\)/";

	private $params = [
		null, 'serverIp', 'serverPort', 'date', 'time',
		'attackerName', 'attackerId', 'attackerSteam', 'attackerTeam', null, null, null,
		'targetName', 'targetId', 'targetSteam', 'targetTeam', null, null, null,
		'weapon', 'damage', 'armorDamage', 'targetHealth', 'targetArmor', 'hitgroup',
	];

	public function parse($raw)
	{
		$event = null;

		if (preg_match(static::PATTERN, $raw, $c)) {

			$event = new Event();
			$event->type = Event::TYPE_DAMAGE;

			$event->attackerPosition = [
				'x' => intval($c[9]),
				'y' => intval($c[10]),
				'z' => intval($c[11]),
			];

			$event->targetPosition = [
				'x' => intval($c[16]),
				'y' => intval($c[17]),
				'z' => intval($c[18]),
			];

			foreach ($this->params as $key => $param) {
				if($param !== null) {
					$event->$param = $c[$key];
				}
			}
		}

		return $event;
	}
}