<?php

namespace App\Http\Controllers;

use App\Classes\RankingMapper;
use Illuminate\Http\Request;

class RankingController extends Controller
{
	public function process($stub)
	{
		try {
			$runner = RankingMapper::getRunnerByStub($stub);
		} catch (\Exception $e) {
			flash()->error("Could not generate ranking data: <strong>{$e->getMessage()}</strong>");

			return back();
		}

		$ranking = $runner::getRanking();

		return view($runner::getView(), [
			'ranking' => $ranking,
			'runner'  => $runner,
		]);
	}
}
