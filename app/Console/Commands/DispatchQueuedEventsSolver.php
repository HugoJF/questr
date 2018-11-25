<?php

namespace App\Console\Commands;

use App\Classes\EventParser;
use App\Classes\EventSolver;
use App\Jobs\SolveQueuedEvents;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class DispatchQueuedEventsSolver extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'events:solve';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Dispatches an event solver';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->info('Dispatching event!');
		SolveQueuedEvents::dispatchNow($this);
	}
}
