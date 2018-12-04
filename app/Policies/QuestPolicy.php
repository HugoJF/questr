<?php

namespace App\Policies;

use App\User;
use App\Quest;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestPolicy
{
	use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

	/**
	 * Determine whether the user can view the quest.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Quest $quest
	 *
	 * @return mixed
	 */
	public function view(User $user, Quest $quest = null)
	{
		return true;
	}

	/**
	 * Determine whether the user can create quests.
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
	 * Determine whether the user can update the quest.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Quest $quest
	 *
	 * @return mixed
	 */
	public function update(User $user, Quest $quest)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the quest.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Quest $quest
	 *
	 * @return mixed
	 */
	public function delete(User $user, Quest $quest)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the quest.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Quest $quest
	 *
	 * @return mixed
	 */
	public function restore(User $user, Quest $quest)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the quest.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Quest $quest
	 *
	 * @return mixed
	 */
	public function forceDelete(User $user, Quest $quest)
	{
		return false;
	}

	/**
	 * Determine whether the user can participate in the quest.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Quest $quest
	 *
	 * @return mixed
	 */
	public function play(User $user, Quest $quest)
	{
		return true;
	}
}
