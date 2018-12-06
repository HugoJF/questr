<?php

namespace App\Console\Commands;

use App\Classes\KVParser;
use App\ShopItem;
use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;

class GenerateShopData extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'shop:generate {tfa}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generates fresh data from BitSkins API';

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
		$rawBsData = $this->getBitSkinsData($this->argument('tfa'));

		$rawWeaponData = $this->getWeaponData();
		$bsData = collect($rawBsData->prices);
		$weaponData = collect($rawWeaponData['Skins']);

		$this->info("Collected {$bsData->count()} prices");
		$bsData = $bsData->reject(function ($item) {
			return
				str_contains($item->market_hash_name, 'Sealed') ||
				str_contains($item->market_hash_name, 'Sticker') ||
				str_contains($item->market_hash_name, 'Gloves') ||
				str_contains($item->market_hash_name, 'Souvenir');
		});
		$this->info("After filtering: {$bsData->count()}");
		$bsData = $bsData->reject(function ($item) use ($weaponData) {
			if (preg_match('/\| (.*?) \(/', $item->market_hash_name, $matches) !== 1) {
				return true;
			}

			if (!$weaponData->has($matches[1])) {
				return true;
			}

			if (!$item->icon_url) {
				return true;
			}

			return false;
		});

		$mapped = $bsData->map(function ($item) use ($weaponData) {
			if (preg_match('/(★ )?(StatTrak™)? ?(.*?) \| (.*?) \((.*?)\)/', $item->market_hash_name, $matches) === 1) {
				if (!$this->isAssoc($weaponData[ $matches[4] ])) {
					$nameToShort = config('constants.name-to-short');
					$itemName = ($matches[1] ?? '') . $matches[3];
//					try {
						$short = $nameToShort[ $itemName ];
//					} catch (\Exception $e) {
//						dd($item->market_hash_name);
//					}
					$data = $weaponData[ $matches[4] ];
					foreach ($data as $d) {
						$classes = preg_split('/;/', $d['classes']);
						if (in_array('weapon_' . $short, $classes)) {
							$item->index = $d['index'];
							break;
						}
					}
				} else {
					$item->index = $weaponData[ $matches[4] ]['index'];
				}
			}

			return $item;
		});

		// TODO: this should not be truncated to avoid breaking foreign keys
		ShopItem::truncate();

		$mapped->each(function ($item, $key) {
			preg_match('/(★ )?(StatTrak™)? ?(.*?) \| (.*?) \((.*?)\)/', $item->market_hash_name, $matches);

			$i = ShopItem::make();
			$i->fill((array)$item);
			$i->price = ceil($item->price);
			if ($matches[2]) {
				$i->stattrak = true;
			} else {
				$i->stattrak = false;
			}
			$i->item_name = trim(($matches[1] ?? '') . ' ' . $matches[3]);
			$i->skin_name = $matches[4];
			$i->condition = $matches[5];
			$i->save();
		});
	}

	private function getWeaponData()
	{
		$path = app_path('Data/weapons_english.cfg');
		$file = fopen($path, 'r');
		$content = fread($file, filesize($path));
		fclose($file);

		$kv = new KVParser();
		$kv->parsing = $content;
		$kv->root();

		return $kv->result;
	}

	private function getBitSkinsData($tfa)
	{
		return cache()->remember('bit-skins-api', 60, function () use ($tfa) {

			$api = config('app.bit-skins-key');

			$result = Curl::to("https://bitskins.com/api/v1/get_all_item_prices/");

			$result->withData([
				'api_key' => $api,
				'code'    => $tfa,
				'app_id'  => 730,
			]);

			$result->asJson();

			return $result->get();
		});
	}

	function isAssoc(array $arr)
	{
		if ([] === $arr)
			return false;

		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}
