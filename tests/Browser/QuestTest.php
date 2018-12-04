<?php

namespace Tests\Browser;

use App\Quest;
use App\QuestProgress;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Browser\Pages\QuestListPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class QuestTest extends DuskTestCase
{
	use DatabaseMigrations;
	use RefreshDatabase;

	/*
	 * META
	 */
	public function test_quest_with_enough_progress_can_be_finished()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'reward'  => 10,
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);
		$questProgress = factory(QuestProgress::class)->create([
			'quest_id' => $quest->id,
			'progress' => $quest->goal + 1,
			'user_id'  => $user->id,
		]);

		$this->browse(function (Browser $browser) use ($quest, $user) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee('Completed')
					->assertSee('Finish')
					->assertSee('Balance: 0')
					->click('#finish')
					->assertSee('Congratulations')
					->assertSee('Finished')
					->assertSee('Rewarded')
					->assertSee('Balance: 10');
		});
	}

	public function test_quest_without_enough_progress_can_not_be_finished()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);
		$questProgress = factory(QuestProgress::class)->create([
			'quest_id' => $quest->id,
			'progress' => $quest->goal - 1,
			'user_id'  => $user->id,
		]);

		$this->browse(function (Browser $browser) use ($quest, $user) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee('In progress')
					->assertSee('View details')
					->assertSee('Balance: 0')
					->visit(route('quests.finish', $quest))
					->assertSee('You must complete the goal of the quest before finishing it!')
					->assertSee('In progress')
					->assertSee('View details')
					->assertSee('Balance: 0');
		});
	}

	/*
	 * HIDDEN
	 */
	public function test_hidden_quests_are_not_visible()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => true,
			'code'    => 'AAA',
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);

		$this->browse(function (Browser $browser) use ($user, $quest) {
			$browser->loginAs($user)
					->visit(new QuestListPage)
					->assertDontSee($quest->title);
		});
	}

	public function test_hidden_quests_can_be_accessed_by_direct_link()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => true,
			'code'    => 'AAA',
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);

		$this->browse(function (Browser $browser) use ($user, $quest) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest->code))
					->assertSee($quest->title);
		});
	}

	/*
	 * VISIBLE
	 */
	public function test_visible_quests_are_visible()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);

		$this->browse(function (Browser $browser) use ($user, $quest) {
			$browser->loginAs($user)
					->visit(new QuestListPage)
					->assertSee($quest->title);
		});
	}

	/*
	 * LOCKED
	 */
	public function test_locked_quests_are_shown_as_locked()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->addDay(1),
			'endAt'   => Carbon::now()->addDay(2),
		]);

		$this->browse(function (Browser $browser) use ($user, $quest) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee('Unlock in')
					->assertSee('Locked');
		});
	}

	public function test_locked_quests_cannot_be_started()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->addDay(1),
			'endAt'   => Carbon::now()->addDay(2),
		]);

		$this->browse(function (Browser $browser) use ($user, $quest) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))// access a page to be redirected back
					->assertSee('Locked')
					->visit(route('quests.start', $quest))
					->assertUrlIs(route('quests.show', $quest))
					->waitForText('Quest details')
					->assertSee('You cannot start locked quests!');
		});
	}

	/*
	 * AVAILABLE
	 */
	public function test_available_quests_are_shown_as_available()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(2),
		]);

		$this->browse(function (Browser $browser) use ($user, $quest) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee('Available')
					->assertSee('Start');
		});
	}

	public function test_available_quests_can_be_started()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'cost'    => 0,
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);

		$this->browse(function (Browser $browser) use ($user, $quest) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee('Available')
					->assertSee('Start')
					->click('#start')
					->assertSee("Quest {$quest->title} has started")
					->assertSee('View details')
					->assertSee('In progress');
		});
	}

	/*
	 * EXPIRED
	 */
	public function test_expired_quests_are_shown_as_expired()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(2),
			'endAt'   => Carbon::now()->subDay(1),
		]);

		$this->browse(function (Browser $browser) use ($user, $quest) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee('Expired');
		});
	}

	public function test_expired_quests_cannot_be_started()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(2),
			'endAt'   => Carbon::now()->subDay(1),
		]);

		$this->browse(function (Browser $browser) use ($user, $quest) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee('Expired')
					->visit(route('quests.start', $quest))
					->assertUrlIs(route('quests.show', $quest))
					->waitForText('Quest details')
					->assertSee('You cannot start expired quests!');
		});
	}

	public function test_expired_quests_cannot_be_finished()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(2),
			'endAt'   => Carbon::now()->subDay(1),
		]);

		$this->browse(function (Browser $browser) use ($user, $quest) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee('Expired')
					->visit(route('quests.finish', $quest))
					->assertUrlIs(route('quests.show', $quest))
					->waitForText('Quest details')
					->assertSee('You must complete the goal of the quest before finishing it!');
		});
	}

	/*
	 * FAILED
	 */
	public function test_failed_quests_are_shown_as_failed()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(2),
			'endAt'   => Carbon::now()->subDay(1),
		]);
		$questProgress = factory(QuestProgress::class)->create([
			'quest_id' => $quest->id,
			'progress' => $quest->goal - 1,
			'user_id'  => $user->id,
		]);

		$this->browse(function (Browser $browser) use ($quest, $user) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee('Failed');
		});
	}

	public function test_failed_quests_cannot_be_finished()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(2),
			'endAt'   => Carbon::now()->subDay(1),
		]);
		$questProgress = factory(QuestProgress::class)->create([
			'quest_id' => $quest->id,
			'progress' => $quest->goal - 1,
			'user_id'  => $user->id,
		]);

		$this->browse(function (Browser $browser) use ($quest, $user) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->visit(route('quests.finish', $quest))
					->assertUrlIs(route('quests.show', $quest))
					->assertSee($quest->title)
				// TODO: improve failure detection
					->assertSee('You must complete the goal of the quest before finishing it!');
		});
	}

	/*
	 * IN PROGRESS
	 */
	public function test_in_progress_quests_are_shown_as_in_progress()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);
		$questProgress = factory(QuestProgress::class)->create([
			'quest_id' => $quest->id,
			'progress' => $quest->goal - 1,
			'user_id'  => $user->id,
		]);

		$this->browse(function (Browser $browser) use ($quest, $user) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee('In progress')
					->assertSee('View details');
		});
	}

	public function test_in_progress_quests_cannot_be_started_again()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);
		$questProgress = factory(QuestProgress::class)->create([
			'quest_id' => $quest->id,
			'progress' => $quest->goal - 1,
			'user_id'  => $user->id,
		]);

		$this->browse(function (Browser $browser) use ($quest, $user) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->visit(route('quests.start', $quest))
					->assertUrlIs(route('quests.show', $quest))
					->assertSee($quest->title)
					->assertSee('In progress')
					->assertSee('View details')
					->assertSee('Quest is already in progress!');
		});
	}

	/*
	 * REWARDED
	 */
	public function test_rewarded_quests_are_shown_as_rewarded()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);
		$questProgress = factory(QuestProgress::class)->create([
			'quest_id' => $quest->id,
			'progress' => $quest->goal + 1,
			'user_id'  => $user->id,
		]);

		$this->browse(function (Browser $browser) use ($quest, $user) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->visit(route('quests.finish', $quest))
					->assertUrlIs(route('quests.show', $quest))
					->assertSee($quest->title)
					->assertSee('Finished')
					->assertSee('Rewarded')
					->assertSee('Congratulations');
		});
	}

	public function test_rewarded_quests_cannot_be_rewarded_again()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);
		$questProgress = factory(QuestProgress::class)->create([
			'quest_id' => $quest->id,
			'progress' => $quest->goal + 1,
			'user_id'  => $user->id,
		]);

		$this->browse(function (Browser $browser) use ($quest, $user) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->visit(route('quests.finish', $quest))
					->visit(route('quests.finish', $quest))
					->assertUrlIs(route('quests.show', $quest))
					->assertSee($quest->title)
					->assertSee('Finished')
					->assertSee('Rewarded')
					->assertSee('You cannot get rewarded multiple times by the same quest!');
		});
	}

	/*
	 * COMPLETED
	 */
	public function test_completed_quests_are_shown_as_completed()
	{
		$user = factory(User::class)->create();
		$quest = factory(Quest::class)->create([
			'hidden'  => false,
			'startAt' => Carbon::now()->subDay(1),
			'endAt'   => Carbon::now()->addDay(1),
		]);
		$questProgress = factory(QuestProgress::class)->create([
			'quest_id' => $quest->id,
			'progress' => $quest->goal + 1,
			'user_id'  => $user->id,
		]);

		$this->browse(function (Browser $browser) use ($quest, $user) {
			$browser->loginAs($user)
					->visit(route('quests.show', $quest))
					->assertSee($quest->title)
					->assertSee('Completed')
					->assertSee('Finish');
		});
	}
}
