<?php

namespace App;

use App\Classes\QuestMapper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Quest extends Model
{
	protected $fillable = [
		'title', 'description', 'cost', 'type', 'goal', 'reward', 'hidden', 'code', 'startAt', 'endAt',
	];

	protected $dates = [
		'startAt', 'endAt',
	];

	public function getRouteKeyName()
	{
		return $this->getAttribute('hidden') ? 'code' : parent::getRouteKeyName();
	}

	public function questProgresses()
	{
		return $this->hasMany(QuestProgress::class);
	}

	public function questFilters()
	{
		return $this->hasMany(QuestFilter::class);
	}

	public function transaction()
	{
		return $this->morphMany(Transaction::class, 'owner');
	}

	public function getRunner()
	{
		$runner = QuestMapper::getRunnerBase($this->type);

		$runner->setQuest($this);

		return $runner;
	}

	public function scopeVisible($query)
	{
		return $query->where('hidden', 0);
	}

	public function scopeHidden($query)
	{
		return $query->where('hidden', 1);
	}

	public function getAvailableAttribute()
	{
		return $this->startAt->isPast() && $this->endAt->isFuture();
	}

	public function getLockedAttribute()
	{
		return $this->startAt->isFuture() && $this->endAt->isFuture();
	}

	public function getExpiredAttribute()
	{
		return $this->startAt->isPast() && $this->endAt->isPast();
	}

	public function inProgress($user)
	{
		if (!$user) {
			if (Auth::check()) {
				$user = Auth::user();
			} else {
				return false;
			}
		}

		if ($user) {
			return $user->questProgresses()->where('quest_id', $this->id)->exists();
		} else {
			return false;
		}
	}

	public function success($user)
	{
		$questProgress = $this->getQuestProgress($user);

		if ($questProgress) {
			return $questProgress->progress >= $this->goal;
		} else {
			return null;
		}
	}

	public function finished($user)
	{
		$questProgress = $this->getQuestProgress($user);


		if ($questProgress) {
			return $questProgress->finished_at !== null;
		} else {
			return null;
		}
	}

	public function failed($user)
	{
		$questProgress = $this->getQuestProgress($user);

		if ($questProgress) {
			return $this->expired && $questProgress->progress != 0;
		} else {
			return null;
		}
	}

	public function progress($user)
	{
		$questProgress = $this->getQuestProgress($user);

		if ($questProgress) {
			return $questProgress->progress;
		} else {
			return null;
		}
	}

	public function getQuestProgress($user)
	{
		if (!$user) {
			if (Auth::check()) {
				$user = Auth::user();
			} else {
				return null;
			}
		}

		if ($this->relationLoaded('questProgresses') && $this->questProgresses->contains('user_id', $user->id)) {
			$questProgress = $this->questProgresses->where('user_id', $user->id)->first();
		} else {
			$questProgress = $this->questProgresses()->where('user_id', $user->id)->first();
		}
		if ($questProgress) {
			return $questProgress;
		} else {
			return null;
		}
	}
}
