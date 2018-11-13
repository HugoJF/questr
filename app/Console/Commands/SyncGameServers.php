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


	protected $nameToShort = [
		'AK-47'             => 'ak47',
		'AUG'               => 'aug',
		'AWP'               => 'awp',
		'CZ75-Auto'         => 'cz75a',
		'Desert Eagle'      => 'deagle',
		'Dual Berettas'     => 'elite',
		'FAMAS'             => 'famas',
		'Five-SeveN'        => 'fiveseven',
		'G3SG1'             => 'g3sg1',
		'Galil AR'          => 'galilar',
		'Glock-18'          => 'glock',
		'M249'              => 'm249',
		'M4A1-S'            => 'm4a1_silencer',
		'M4A4'              => 'm4a1',
		'MAC-10'            => 'mac10',
		'MAG-7'             => 'mag7',
		'MP5-SD'            => 'mp5sd',
		'MP7'               => 'mp7',
		'MP9'               => 'mp9',
		'Negev'             => 'negev',
		'Nova'              => 'nova',
		'P2000'             => 'hkp2000',
		'P250'              => 'p250',
		'P90'               => 'p90',
		'PP-Bizon'          => 'bizon',
		'R8 Revolver'       => 'revolver',
		'SCAR-20'           => 'scar20',
		'SG 553'            => 'sg556',
		'SSG 08'            => 'ssg08',
		'Sawed-Off'         => 'sawedoff',
		'Tec-9'             => 'tec9',
		'UMP-45'            => 'ump45',
		'USP-S'             => 'usp_silencer',
		'XM1014'            => 'xm1014',
		'★ Bayonet'         => 'bayonet',
		'★ Bowie Knife'     => 'knife_survival_bowie',
		'★ Butterfly Knife' => 'knife_butterfly',
		'★ Falchion Knife'  => 'knife_falchion',
		'★ Flip Knife'      => 'knife_flip',
		'★ Gut Knife'       => 'knife_gut',
		'★ Huntsman Knife'  => 'knife_tactical',
		'★ Karambit'        => 'knife_karambit',
		'★ M9 Bayonet'      => 'knife_m9_bayonet',
		'★ Navaja Knife'    => 'knife_gypsy_jackknife',
		'★ Shadow Daggers'  => 'knife_push',
		'★ Stiletto Knife'  => 'knife_stiletto',
		'★ Talon Knife'     => 'knife_widowmaker',
		'★ Ursus Knife'     => 'knife_ursus',

	];

	protected $floats = [
		'Factory New'    => [
			0, 0.07,
		],
		'Minimal Wear'   => [
			0.07, 0.15,
		],
		'Field-Tested'   => [
			0.15, 0.37,
		],
		'Well-Worn'      => [
			0.37, 0.44,
		],
		'Battle-Scarred' => [
			0.44, 1,
		],
	];

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
		$unsyncedItems = Inventory::whereColumn('synced', '!=', 'equipped')->with(['user', 'item'])->get();
		$count = $unsyncedItems->count();
		$this->comment("Found $count unsynced items...");
		foreach ($unsyncedItems as $item) {
			$this->line("Syncing item #{$item->id}");
			if($item->equipped) {
				$this->syncItem($item);
			}
			$item->synced = $item->equipped;
			$item->save();
		}
	}

	public function syncItem(Inventory $inv)
	{
		$steamId = $inv->user->steam_id;
		$index = $inv->item->index;

		$itemName = $inv->item->item_name;
		$short = $this->weaponNameToShort($itemName);

		$condition = $inv->item->conditions;
		$float = $this->conditionNameToFloat($condition);

		$this->syncColumn($steamId, $short, $index, $float);
	}

	public function syncColumn($steamid, $short, $skinIndex, $float, $trak = 0, $trakCount = 0, $tag = '')
	{

		DB::connection('kaganus_weapons')->table('weapons')->where('steamid', $steamid)->update([
			$short                => $skinIndex,
			"{$short}_float"      => $float,
			"{$short}_trak"       => $trak,
			"{$short}_trak_count" => $trakCount,
			"{$short}_tag"        => $tag,
		]);
	}

	public function weaponNameToShort($weaponName)
	{
		$trimmed = trim($weaponName);

		if (array_key_exists($trimmed, $this->nameToShort)) {
			return $this->nameToShort[ $trimmed ];
		} else {
			throw new \Exception("Unexpected weapon name $weaponName");
		}
	}

	public function conditionNameToFloat($conditionName)
	{
		$trimmed = trim($conditionName);

		if (array_key_exists($trimmed, $this->floats)) {
			list($low, $high) = $this->floats[ $trimmed ];

			return rand($low, $high);
		} else {
			return 1;
		}
	}

}
