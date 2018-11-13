<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/1/2018
 * Time: 7:47 AM
 */

namespace App\Classes;

use App\User;

class Event
{
	const TYPE_DAMAGE = 1;

	public $type;

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

	public function getAttackerUser()
	{
		$user = User::where('steam_id', $this->attackerSteam)->first();

		return $user;
	}

}