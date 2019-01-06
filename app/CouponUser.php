<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CouponUser extends Pivot
{
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function coupon()
	{
		return $this->belongsTo(Coupon::class);
	}
}
