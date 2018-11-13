<?php

namespace App\Console\Commands;

use App\Inventory;
use Illuminate\Console\Command;

class ClearExpiredEquippedItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Un-equips any expired items';

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
    	$cleared = Inventory::expired()->update(['equipped' => 0]);

    	if($cleared) {
    		$this->info("Successfully cleared equips!");
		} else {
    		$this->error('Error while clearing expired equips!');
		}
    }
}
