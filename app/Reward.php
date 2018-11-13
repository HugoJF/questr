<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
	public function questProgress()
	{
		return $this->belongsTo(QuestProgress::class);
	}
}
