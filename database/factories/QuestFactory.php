<?php

use Faker\Generator as Faker;

$factory->define(\App\Quest::class, function (Faker $faker) {
	$a = $faker->dateTimeBetween('-30 days', '+30 days');
	$b = $faker->dateTimeBetween('-30 days', '+30 days');

	if ($a > $b) {
		$c = $a;
		$a = $b;
		$b = $c;
	}

	$hidden = $faker->boolean(80);

	return [
		'title'       => $faker->sentence,
		'description' => $faker->text,
		'cost'        => rand(10, 100),
		'goal'        => rand(10, 1000),
		'reward'      => rand(10, 100),
		'hidden'      => $hidden,
		'code'        => $hidden ? $faker->randomLetter . $faker->randomLetter . $faker->randomLetter : '',
		'type'        => 'KILL_COUNT',
		'startAt'     => $a,
		'endAt'       => $b,
	];
});
