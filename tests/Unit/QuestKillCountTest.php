<?php

namespace Tests\Unit;

use App\Classes\EventParser;
use App\Classes\EventSolver;
use App\Quest;
use App\QuestFilter;
use App\QuestProgress;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestKillCountTest extends TestCase
{
	use RefreshDatabase;
	use DatabaseMigrations;

	private $raw = '143.202.39.221:5001 - 11/01/2018 - 17:06:24: "CLOWN-<3752><STEAM_1:0:180614322><CT>" [66 -2166 -36] attacked "Lukz -名誉-<3758><STEAM_1:1:119971439><TERRORIST>" [-716 -1666 -172] with "awp" (damage "110") (damage_armor "1") (health "0") (armor "98") (hitgroup "chest")';
	private $eventParser;
	private $eventSolver;
	private $perTestEventCount = 2;

	private $event;
	private $createdUser;
	private $createdQuest;
	private $createdQuestProgress;

	private function emitKillEvent($count = 1)
	{
		$result = 0;
		for ($i = 0; $i < $count; $i++) {
			$result += $this->eventSolver->solve($this->event);
		}

		return $result;
	}

	public function test_quest_kill_count_will_count_kills_correctly()
	{
		$this->runQuestKillCountTest(2, null);
	}

	public function test_quest_kill_count_will_not_count_if_filter_is_for_another_weapon()
	{
		$this->runQuestKillCountTest(0, function () {
			factory(QuestFilter::class)->create([
				'quest_id' => $this->createdQuest->id,
				'key'      => 'weapon',
				'value'    => 'ak47',
			]);
		});
	}

	public function test_quest_kill_count_will_count_if_filter_allows_weapon()
	{
		$this->runQuestKillCountTest(2, function () {
			factory(QuestFilter::class)->create([
				'quest_id' => $this->createdQuest->id,
				'key'      => 'weapon',
				'value'    => 'awp',
			]);
		});
	}

	private function runQuestKillCountTest($expectedProgress, $onBeforeEmitEvent)
	{
		$this->eventParser = new EventParser();
		$this->eventSolver = new EventSolver();

		$this->event = $this->eventParser->parse($this->raw);

		$this->createdUser = factory(User::class)->create([
			'steam_id' => 'STEAM_1:0:180614322',
		]);

		$this->createdQuest = factory(Quest::class)->create([
			'goal' => 10,
			'type' => 'KILL_COUNT',
		]);

		// TODO: quest should query what is the main user
		$user = $this->event->getAttackerUser();

		if (!$user) {
			throw new \Exception('User could not be found!');
		}

		$this->createdQuestProgress = factory(QuestProgress::class)->create([
			'user_id'  => $user->id,
			'quest_id' => $this->createdQuest->id,
			'progress' => 0,
		]);

		if ($onBeforeEmitEvent) {
			$onBeforeEmitEvent();
		}

		$result = $this->emitKillEvent($this->perTestEventCount);

		$this->assertEquals($expectedProgress, $result);

		$this->assertDatabaseHas('quest_progresses', [
			'progress' => $expectedProgress,
			'user_id'  => $user->id,
			'quest_id' => $this->createdQuest->id,
		]);
	}
}
