<?php

namespace Tests\Unit;

use App\Classes\Event;
use App\Classes\EventParser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventParserTest extends TestCase
{
	use RefreshDatabase;
	use DatabaseMigrations;

	public function test_event_parser_will_parse_raw_events_correctly()
	{
		$raw = '143.202.39.221:5001 - 11/01/2018 - 17:06:24: "CLOWN-<3752><STEAM_1:0:180614322><CT>" [66 -2166 -36] attacked "Lukz -名誉-<3758><STEAM_1:1:119971439><TERRORIST>" [-716 -1666 -172] with "awp" (damage "110") (damage_armor "1") (health "0") (armor "98") (hitgroup "chest")';

		$eventParser = new EventParser();
		$event = $eventParser->parse($raw);

		$this->assertEquals(Event::TYPE_DAMAGE, $event::getType());
		$this->assertEquals('11/01/2018', $event->date);
		$this->assertEquals('17:06:24', $event->time);
		$this->assertEquals('CLOWN-', $event->attackerName);
		$this->assertEquals('3752', $event->attackerId);
		$this->assertEquals('STEAM_1:0:180614322', $event->attackerSteam);
		$this->assertEquals('CT', $event->attackerTeam);
		$this->assertEquals(['x' => 66, 'y' => -2166, 'z' => -36], $event->attackerPosition);
		$this->assertEquals('Lukz -名誉-', $event->targetName);
		$this->assertEquals('3758', $event->targetId);
		$this->assertEquals('STEAM_1:1:119971439', $event->targetSteam);
		$this->assertEquals('TERRORIST', $event->targetTeam);
		$this->assertEquals(['x' => -716, 'y' => -1666, 'z' => -172], $event->targetPosition);
		$this->assertEquals('awp', $event->weapon);
		$this->assertEquals('110', $event->damage);
		$this->assertEquals('1', $event->armorDamage);
		$this->assertEquals('0', $event->targetHealth);
		$this->assertEquals('98', $event->targetArmor);
		$this->assertEquals('chest', $event->hitgroup);
	}
}
