<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestProgress extends Model
{
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function quest()
	{
		return $this->belongsTo(Quest::class);
	}

	public function reward()
	{
		return $this->hasOne(Reward::class);
	}
}