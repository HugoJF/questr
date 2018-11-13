<?php

use Faker\Generator as Faker;

$factory->define(\App\QuestProgress::class, function (Faker $faker) {
	$quest = \App\Quest::inRandomOrder()->first();
    return [
    	'progress' => rand(0, $quest->goal * 2),
		'quest_id' => $quest->id,
		'user_id' =>  \App\User::inRandomOrder()->first(),
    ];
});
