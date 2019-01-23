<?php

namespace App\Policies;

use App\User;
use App\ShopItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopItemPolicy
{
	use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

	/**
	 * Determine whether the user can view the shop item.
	 *
	 * @param  \App\User     $user
	 * @param  \App\ShopItem $shopItem
	 *
	 * @return mixed
	 */
	public function view(User $user, ShopItem $shopItem = null)
	{
		return true;
	}

	/**
	 * Determine whether the user can create shop items.
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
	 * Determine whether the user can update the shop item.
	 *
	 * @param  \App\User     $user
	 * @param  \App\ShopItem $shopItem
	 *
	 * @return mixed
	 */
	public function update(User $user, ShopItem $shopItem)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the shop item.
	 *
	 * @param  \App\User     $user
	 * @param  \App\ShopItem $shopItem
	 *
	 * @return mixed
	 */
	public function delete(User $user, ShopItem $shopItem)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the shop item.
	 *
	 * @param  \App\User     $user
	 * @param  \App\ShopItem $shopItem
	 *
	 * @return mixed
	 */
	public function restore(User $user, ShopItem $shopItem)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the shop item.
	 *
	 * @param  \App\User     $user
	 * @param  \App\ShopItem $shopItem
	 *
	 * @return mixed
	 */
	public function forceDelete(User $user, ShopItem $shopItem)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently buy the shop item.
	 *
	 * @param  \App\User     $user
	 * @param  \App\ShopItem $shopItem
	 *
	 * @return mixed
	 */
	public function buy(User $user, ShopItem $shopItem = null)
	{
		return true;
	}
}
