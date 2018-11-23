<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CouponUser extends Pivot
{
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function coupon()
	{
		return $this->belongsTo(Coupon::class);
	}
}
