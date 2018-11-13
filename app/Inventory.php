<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
	protected $dates = ['ends_at'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function item()
	{
		return $this->belongsTo(ShopItem::class);
	}

	public function getExpiredAttribute()
	{
		return $this->ends_at->isPast();
	}

	public function scopeExpired($query)
	{
		$query->where('ends_at', '<', Carbon::now());
	}


}
