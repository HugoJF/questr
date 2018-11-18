<?php

namespace App\Policies;

use App\User;
use App\Inventory;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
{
	use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

	/**
	 * Determine whether the user can view the inventory.
	 *
	 * @param  \App\User      $user
	 * @param  \App\Inventory $inventory
	 *
	 * @return mixed
	 */
	public function view(User $user, Inventory $inventory)
	{
		return true;
	}

	/**
	 * Determine whether the user can view the inventory.
	 *
	 * @param  \App\User      $user
	 * @param  \App\Inventory $inventory
	 *
	 * @return mixed
	 */
	public function equip(User $user, Inventory $inventory)
	{
		return true;
	}
}
