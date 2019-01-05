<?php
namespace App\Console\Commands;

use App\Transaction;
use App\User;
use Illuminate\Console\Command;

class GenerateTransaction extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'transactions:generate {amount}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

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
		$user = User::where('steam_id', 'STEAM_1:1:36509127')->first();
		$value = $this->argument('amount');

		$t = Transaction::make();
		$t->value = $value;
		$t->user()->associate($user);
		$t->owner()->associate($user);
		$c = $t->save();

		if($c) {
			$this->info("Transaction with $value coins created!");
		} else {
			$this->error('Error generating transaction');
		}
	}
}
