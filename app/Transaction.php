<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	protected $guarded = ['value'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function owner()
	{
		return $this->morphTo();
	}
}
