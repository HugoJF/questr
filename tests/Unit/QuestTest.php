<?php

namespace Tests\Unit;

use App\Quest;
use App\QuestProgress;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestTest extends TestCase
{
	use RefreshDatabase;
	use DatabaseMigrations;

	public function test_available_attribute_works()
	{
		$quest = factory(Quest::class)->create([
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);

		$this->assertTrue($quest->available);
		$this->assertFalse($quest->locked);
		$this->assertFalse($quest->expired);
		$this->assertFalse($quest->inProgress);
	}

	public function test_locked_attribute_works()
	{
		$quest = factory(Quest::class)->create([
			'startAt' => Carbon::now()->addDay(1),
			'endAt'   => Carbon::now()->addDay(2),
		]);

		$this->assertFalse($quest->available);
		$this->assertTrue($quest->locked);
		$this->assertFalse($quest->expired);
		$this->assertFalse($quest->inProgress);
	}

	public function test_expired_attribute_works()
	{
		$quest = factory(Quest::class)->create([
			'startAt' => Carbon::now()->subDay(2),
			'endAt'   => Carbon::now()->subDay(1),
		]);

		$this->assertFalse($quest->available);
		$this->assertFalse($quest->locked);
		$this->assertTrue($quest->expired);
		$this->assertFalse($quest->inProgress);
	}

	public function test_in_progress_attribute_works()
	{
		$user = factory(User::class)->create();

		$quest = factory(Quest::class)->create([
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);

		$questProgress = factory(QuestProgress::class)->create([
			'user_id'  => $user->id,
			'quest_id' => $quest->id,
		]);

		Auth::login($user);

		$this->assertTrue($quest->available);
		$this->assertFalse($quest->locked);
		$this->assertFalse($quest->expired);
		$this->assertTrue($quest->inProgress);
	}

	public function test_success_attribute_works()
	{
		$user = factory(User::class)->create();

		$quest = factory(Quest::class)->create([
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
			'goal'    => 10,
		]);

		$questProgress = factory(QuestProgress::class)->create([
			'progress' => 15,
			'user_id'  => $user->id,
			'quest_id' => $quest->id,
		]);

		Auth::login($user);

		$this->assertTrue($quest->success);
	}

	public function test_success_attribute_works_with_attributes_pre_loaded()
	{
		$user = factory(User::class)->create();

		$quest = factory(Quest::class)->create([
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
			'goal'    => 10,
		]);

		$questProgress = factory(QuestProgress::class)->create([
			'progress' => 15,
			'user_id'  => $user->id,
			'quest_id' => $quest->id,
		]);

		$quest->load('questProgresses');

		Auth::login($user);

		$this->assertTrue($quest->success);
	}

	public function test_success_attribute_works_when_user_has_not_finished_quest()
	{
		$user = factory(User::class)->create();

		$quest = factory(Quest::class)->create([
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
			'goal'    => 10,
		]);

		$questProgress = factory(QuestProgress::class)->create([
			'progress' => 5,
			'user_id'  => $user->id,
			'quest_id' => $quest->id,
		]);

		Auth::login($user);

		$this->assertFalse($quest->success);
	}
}
