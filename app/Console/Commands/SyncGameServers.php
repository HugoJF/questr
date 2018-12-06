<?php

namespace App\Console\Commands;

use App\Inventory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncGameServers extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'weapons:sync';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	protected $knifeClasses;

	protected $nameToShort;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->knifeClasses = config('constants.knife-classes');
		$this->nameToShort = config('constants.name-to-short');
		$this->floats = config('constants.floats');

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$unsyncedItems = Inventory::whereColumn('synced', '!=', 'equipped')->with(['user', 'item'])->get();
		$count = $unsyncedItems->count();
		$this->comment("Found $count unsynced items...");
		foreach ($unsyncedItems as $item) {
			$this->line("Syncing item #{$item->id}");
			if ($item->equipped) {
				$this->syncItem($item);
			}
			$item->synced = $item->equipped;
			$item->save();
		}
	}

	public function syncItem(Inventory $inv)
	{
		// References
		$steamId = $inv->user->steam_id;
		$index = $inv->item->index;

		// Translate user-friendly name to short name
		$itemName = $inv->item->item_name;
		$short = $this->weaponNameToShort($itemName);

		// If item is a knife
		if (array_key_exists($short, $this->knifeClasses)) {
			$class = $this->knifeClasses[$short];
			$this->syncKnife($steamId, $class);
		}

		$float = $inv->float;
		$statTrak = (boolean) $inv->item->stattrak;
		$tag = $inv->tag;

		// Sync weapon skins database
		$this->syncColumn($steamId, $short, $index, $float, $statTrak, 0, $tag);
	}

	public function syncKnife($steamid, $class)
	{
		// Update knife class in weapon skins database (used by game servers)
		DB::connection('kaganus_weapons')->table('weapons')->where('steamid', $steamid)->update([
			'knife' => $class,
		]);
	}

	public function syncColumn($steamId, $short, $skinIndex, $float, $trak = 0, $trakCount = 0, $tag = '')
	{
		// Update weapon skins database (used by game servers)
		DB::connection('kaganus_weapons')->table('weapons')->where('steamid', $steamId)->update([
			$short                => $skinIndex,
			"{$short}_float"      => $float,
			"{$short}_trak"       => $trak,
			// Avoid resetting trak_count
//			"{$short}_trak_count" => $trakCount,
			"{$short}_tag"        => $tag,
		]);
	}

	public function weaponNameToShort($weaponName)
	{
		// Remove any white spaces
		$trimmed = trim($weaponName);

		// Check if item exists in array before using it
		if (array_key_exists($trimmed, $this->nameToShort)) {
			return $this->nameToShort[ $trimmed ];
		} else {
			throw new \Exception("Unexpected weapon name $weaponName");
		}
	}
}
