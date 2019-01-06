<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

	/**h
	 * Determine whether the user can view the quest.
	 *
	 * @param  \App\User $user
	 * @param  \App\User $otherUser
	 *
	 * @return mixed
	 */
	public function view(User $user, User $otherUser)
	{
		return $user->id === $otherUser->id;
	}

}
