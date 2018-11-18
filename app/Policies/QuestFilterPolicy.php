<?php

namespace App\Policies;

use App\User;
use App\QuestFilter;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestFilterPolicy
{
	use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

	/**
	 * Determine whether the user can view the quest filter.
	 *
	 * @param  \App\User        $user
	 * @param  \App\QuestFilter $questFilter
	 *
	 * @return mixed
	 */
	public function view(User $user, QuestFilter $questFilter)
	{
		return true;
	}

	/**
	 * Determine whether the user can create quest filters.
	 *
	 * @param  \App\User $user
	 *
	 * @return mixed
	 */
	public function create(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can update the quest filter.
	 *
	 * @param  \App\User        $user
	 * @param  \App\QuestFilter $questFilter
	 *
	 * @return mixed
	 */
	public function update(User $user, QuestFilter $questFilter)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the quest filter.
	 *
	 * @param  \App\User        $user
	 * @param  \App\QuestFilter $questFilter
	 *
	 * @return mixed
	 */
	public function delete(User $user, QuestFilter $questFilter)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the quest filter.
	 *
	 * @param  \App\User        $user
	 * @param  \App\QuestFilter $questFilter
	 *
	 * @return mixed
	 */
	public function restore(User $user, QuestFilter $questFilter)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the quest filter.
	 *
	 * @param  \App\User        $user
	 * @param  \App\QuestFilter $questFilter
	 *
	 * @return mixed
	 */
	public function forceDelete(User $user, QuestFilter $questFilter)
	{
		return false;
	}
}
