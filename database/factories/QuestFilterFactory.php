<?php

use Faker\Generator as Faker;

$factory->define(\App\QuestFilter::class, function (Faker $faker) {
	$quest = \App\Quest::inRandomOrder()->first();

	return [
		'key'      => 'weapon',
		'value'    => 'awp',
		'quest_id' => $quest->id,
	];
});
