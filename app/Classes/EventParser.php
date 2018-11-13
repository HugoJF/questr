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
	const PATTERN = "/(\d{1,2}\\/\\d{1,2}\\/\d{1,4})\s-\s(\d{1,2}:\d{1,2}:\d{1,2}):\s\"(.+?)<(\d*)><(STEAM_\d:\d:\d+?)><(.+?)>\"\s\[(-?\d+?)\s(-?\d+?)\s(-?\d+?)\]\sattacked\s\"(.+?)<(\d+?)><(STEAM_\d:\d:\d+?)><(.+?)>\"\s\[(-?\d+?)\s(-?\d+?)\s(-?\d+?)\]\swith\s\"(.+?)\"\s\(damage\s\"(\d+?)\"\)\s\(damage_armor\s\"(\d+?)\"\)\s\(health\s\"(\d+?)\"\)\s\(armor\s\"(\d+?)\"\)\s\(hitgroup\s\"(.+?)\"\)/";

	public function parse($raw)
	{
		$event = null;

		if (preg_match(static::PATTERN, $raw, $c)) {
			$event = new Event();
			$event->type = Event::TYPE_DAMAGE;
			$event->date = $c[1];
			$event->time = $c[2];
			$event->attackerName = $c[3];
			$event->attackerId = $c[4];
			$event->attackerSteam = $c[5];
			$event->attackerTeam = $c[6];
			$event->attackerPosition = [
				'x' => intval($c[7]),
				'y' => intval($c[8]),
				'z' => intval($c[9]),
			];
			$event->targetName = $c[10];
			$event->targetId = $c[11];
			$event->targetSteam = $c[12];
			$event->targetTeam = $c[13];
			$event->targetPosition = [
				'x' => intval($c[14]),
				'y' => intval($c[15]),
				'z' => intval($c[16]),
			];
			$event->weapon = $c[17];
			$event->damage = $c[18];
			$event->armorDamage = $c[19];
			$event->targetHealth = $c[20];
			$event->targetArmor = $c[21];
			$event->hitgroup = $c[22];
		}

		return $event;
	}
}