<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/1/2018
 * Time: 7:54 AM
 */

namespace App\Classes;


use App\Quest;
use App\QuestProgress;
use Illuminate\Support\Facades\Log;

class QuestKillCount extends BaseQuest
{
	public $quest;

	public function accepts(Event $event)
	{
		return $event->type === Event::TYPE_DAMAGE &&
			$event->targetHealth == 0;
	}

	public function run()
	{
		$user = $this->getUser();

		$questProgress = $this->getQuestProgress();

		// User should manually create this
		if (!$questProgress) {
			$name = $user ? $user->id : 'Unknown user';

			Log::info("User {$name} does not have a quest progress, skipping!");

			return false;
		}

		$filters = $this->quest->questFilters->keyBy('key');

		$filter = $this->quest->questFilters()->where('key', 'weapon')->first();

		if ($filter && $filter->value != $this->event->weapon) {
			return false;
		}

		$questProgress->progress++;
		$persisted = $questProgress->save();

		return $persisted;
	}

	public function isComplete()
	{
		return $this->getQuestProgress()->progress >= $this->quest->goal;
	}

	public function getQuestProgress()
	{
		$user = $this->getUser();

		if ($user && $this->quest) {
			return $this->quest->questProgresses()->where('user_id', $this->getUser()->id)->first();
		} else {
			return null;
		}
	}

	public function getQuestFilters()
	{
		return [
			'weapon' => [
				'awp', 'ak47',
			]
		];
	}
}