<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Coupon extends Pivot
{
	protected $dates = ['startAt', 'endAt'];

	public function userUses()
	{
		return $this->hasMany(CouponUser::class, 'coupon_id');
	}
}
