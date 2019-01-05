<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/1/2018
 * Time: 7:47 AM
 */

namespace App\Classes;

use App\User;

class PlayerDamageEvent extends Event
{
	// (\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}):(\d{1,5})\s-\s(\d{1,2}\/\d{1,2}\/\d{1,4})\s-\s(\d{1,2}:\d{1,2}:\d{1,2}):\s"(.+?)<(\d*)><(STEAM_\d:\d:\d+?)><(.+?)>"\s\[(-?\d+?)\s(-?\d+?)\s(-?\d+?)\]\sattacked\s"(.+?)<(\d+?)><(STEAM_\d:\d:\d+?)><(.+?)>"\s\[(-?\d+?)\s(-?\d+?)\s(-?\d+?)\]\swith\s"(.+?)"\s\(damage\s"(\d+?)"\)\s\(damage_armor\s"(\d+?)"\)\s\(health\s"(\d+?)"\)\s\(armor\s"(\d+?)"\)\s\(hitgroup\s"(.+?)"\)

	private const PATTERN = "/(\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}):(\d{1,5})\s-\s(\d{1,2}\/\d{1,2}\/\d{1,4})\s-\s(\d{1,2}:\d{1,2}:\d{1,2}):\s\"(.+?)<(\d*)><(STEAM_\d:\d:\d+?)><(.+?)>\"\s\[(-?\d+?)\s(-?\d+?)\s(-?\d+?)\]\sattacked\s\"(.+?)<(\d+?)><(STEAM_\d:\d:\d+?)><(.+?)>\"\s\[(-?\d+?)\s(-?\d+?)\s(-?\d+?)\]\swith\s\"(.+?)\"\s\(damage\s\"(\d+?)\"\)\s\(damage_armor\s\"(\d+?)\"\)\s\(health\s\"(\d+?)\"\)\s\(armor\s\"(\d+?)\"\)\s\(hitgroup\s\"(.+?)\"\)/";

	public $serverIp;
	public $serverPort;

	public $date;
	public $time;

	public $attackerName;
	public $attackerSteam;
	public $attackerTeam;
	public $attackerId;
	public $attackerPosition;
	public $weapon;

	public $damage;
	public $armorDamage;
	public $hitgroup;

	public $targetName;
	public $targetId;
	public $targetSteam;
	public $targetTeam;
	public $targetPosition;
	public $targetHealth;
	public $targetArmor;

	private static $params = [
		null, 'serverIp', 'serverPort', 'date', 'time',
		'attackerName', 'attackerId', 'attackerSteam', 'attackerTeam', null, null, null,
		'targetName', 'targetId', 'targetSteam', 'targetTeam', null, null, null,
		'weapon', 'damage', 'armorDamage', 'targetHealth', 'targetArmor', 'hitgroup',
	];

	public function getAttackerUser()
	{
		$user = User::where('steam_id', $this->attackerSteam)->first();

		return $user;
	}

	public static function getType()
	{
		return Event::TYPE_DAMAGE;
	}

	public function fill($matches)
	{
		$this->attackerPosition = [
			'x' => intval($matches[9]),
			'y' => intval($matches[10]),
			'z' => intval($matches[11]),
		];

		$this->targetPosition = [
			'x' => intval($matches[16]),
			'y' => intval($matches[17]),
			'z' => intval($matches[18]),
		];

		foreach (static::$params as $key => $param) {
			if ($param !== null) {
				$this->$param = $matches[ $key ];
			}
		}
	}

	public static function build($raw)
	{
		if (preg_match(PlayerDamageEvent::PATTERN, $raw, $matches)) {
			$event = new static();

			$event->fill($matches);

			return $event;
		} else {
			return false;
		}
	}
}