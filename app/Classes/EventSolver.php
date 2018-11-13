<?php

namespace App\Classes;


use App\Quest;

class EventSolver
{
	public function solveRaw($raw)
	{
		$eventParser = new EventParser();

		$event = $eventParser->parse($raw);

		$this->solve($event);
	}

	public function solve(Event $event)
	{
		$successCount = 0;
		foreach (Quest::all() as $quest) {
			$runner = $quest->getRunner();

			if ($runner->accepts($event)) {
				$runner->setEvent($event);
				if ($runner->run()) {
					$successCount++;
				}
			}
		}

		return $successCount;
	}
}