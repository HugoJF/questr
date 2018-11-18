<?php

namespace Tests\Unit;

use App\Classes\EventParser;
use App\Classes\EventSolver;
use App\Quest;
use App\QuestProgress;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventSolverTest extends TestCase
{
	use RefreshDatabase;
	use DatabaseMigrations;

	public function test_event_solver_will_create_quest_progress()
	{
		$raw = '143.202.39.221:5001 - 11/01/2018 - 17:06:24: "CLOWN-<3752><STEAM_1:0:180614322><CT>" [66 -2166 -36] attacked "Lukz -名誉-<3758><STEAM_1:1:119971439><TERRORIST>" [-716 -1666 -172] with "awp" (damage "110") (damage_armor "1") (health "0") (armor "98") (hitgroup "chest")';


		$eventParser = new EventParser();
		$eventSolver = new EventSolver();
		$event = $eventParser->parse($raw);

		$createdUser = factory(User::class)->create([
			'steam_id' => 'STEAM_1:0:180614322',
		]);

		$createdQuest = factory(Quest::class)->create([
			'goal' => 10,
			'type' => 'KILL_COUNT',
		]);

		$createdQuestProgress = factory(QuestProgress::class)->create([
			'user_id'  => $createdUser->id,
			'quest_id' => $createdQuest->id,
			'progress' => 0,
		]);

		// TODO: quest should query what is the main user
		$user = $event->getAttackerUser();

		if (!$user) {
			throw new \Exception('User could not be found!');
		}

		$result = $eventSolver->solve($event);

		$this->assertEquals(1, $result);

		$this->assertDatabaseHas('quest_progresses', [
			'progress' => '1',
			'user_id'  => $user->id,
			'quest_id' => $createdQuest->id,
		]);
	}
}
