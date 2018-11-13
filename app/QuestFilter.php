<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestFilter extends Model
{
	protected $fillable = [
		'key', 'value',
	];

	public function quest()
	{
		return $this->belongsTo(Quest::class);
	}
}
