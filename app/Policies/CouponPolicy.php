<?php

namespace App\Policies;

use App\Coupon;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouponPolicy
{
	use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

	public function index(User $user)
	{
		return false;
	}

	public function edit(User $user)
	{
		return false;
	}

	public function use(User $user, Coupon $coupon = null)
	{
		return true;
	}

	public function create(User $user, Coupon $coupon)
	{
		return false;
	}

	public function delete(User $user, Coupon $coupon)
	{
		return false;
	}

}
