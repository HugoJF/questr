<?php

namespace App\Jobs;

use App\Classes\EventParser;
use App\Classes\EventSolver;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class SolveQueuedEvents implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private $eventsPerJob = 30000;
	private $messageKey = null;

	private $command;

	/**
	 * Create a new job instance.
	 *
	 * @param Command $command
	 */
	public function __construct(Command $command)
	{
		$this->messageKey = config('app.redis-event-key', 'messages');
		$this->command = $command;
		$command->info('Hooked');
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$listSize = Redis::command('llen', [$this->messageKey]);

		$listSize = $listSize > $this->eventsPerJob ? $this->eventsPerJob : $listSize;

		$eventParser = new EventParser();
		$eventSolver = new EventSolver();

		for ($i = 0; $i < $listSize; $i++) {
			$raw = Redis::command('lpop', [$this->messageKey]);
			$this->info("Parsing: ${raw}");
			$event = $eventParser->parse($raw);
			if ($event) {
				$this->info("Event parsed, solving it now!");
				$eventSolver->solve($event);
			}
		}
	}

	private function info($message)
	{
		if ($this->command) {
			$this->command->info($message);
		} else {
			Log::info($message);
		}
	}
}
