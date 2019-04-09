<?php

namespace App\Jobs;

use App\Classes\EventParser;
use App\Classes\EventSolver;
use hugojf\CsgoServerApi\Facades\CsgoApi;
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

	private $processingTime = 30000;
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
		$start = round(microtime(true) * 1000);

		$listSize = Redis::command('llen', [$this->messageKey]);
		$startTime = round(microtime(true) * 1000);
		$duration = 0;

		$eventParser = new EventParser();
		$eventSolver = new EventSolver();

		while ($listSize > 0 && $duration < $this->processingTime) {
			$listSize = Redis::command('llen', [$this->messageKey]);
			$duration = round(microtime(true) * 1000) - $startTime;

			$raw = Redis::command('lpop', [$this->messageKey]);
			$this->info("Parsing: ${raw}");
			$event = $eventParser->parse($raw);
			if ($event) {
				$this->info("Event parsed, solving it now!");
				$eventSolver->solve($event);
			}
		}

		$end = round(microtime(true) * 1000);
		$duration = $end - $start;

		CsgoApi::all()->execute("sm_say Handling of $listSize events took: $duration ms", 1000)->send();
		
		Log::info("Handling of $listSize events took: $duration ms");
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
