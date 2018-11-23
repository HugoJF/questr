<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\ShopItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
	public function index()
	{
		$items = Inventory::with('item')->paginate(12);

		return view('inventories.index', [
			'items' => $items,
		]);
	}

	public function equip(Inventory $inv)
	{
		if ($inv->expired) {
			flash()->warning('You cannot equip expired items!')->important();

			return back();
		}

		//TODO: check ownership

		$item = $inv->item;

		$joinedItems = Auth::user()->inventories()->join('shop_items', 'shop_items.id', '=', 'inventories.item_id');

		$this->clearWeaponEquips($item, clone $joinedItems);
		$this->clearKnifeEquips($item, $joinedItems);

		$inv->equipped = true;

		$inv->save();

		flash()->success("Item <strong>{$item->market_hash_name}</strong> was equipped successfully!")->important();

		return redirect()->back();
	}

	private function clearWeaponEquips(ShopItem $item, $joinedItems): void
	{
		$sameWeapons = $joinedItems->where('shop_items.item_name', $item->item_name)->get(['inventories.id']);

		$this->clearEquips($sameWeapons->pluck('id'));
	}

	private function clearKnifeEquips(ShopItem $item, $joinedItems)
	{
		$knives = config('constants.knives');

		if (in_array($item->item_name, $knives)) {
			$sameWeapons = $joinedItems->whereIn('shop_items.item_name', $knives)->get(['inventories.id']);

			$this->clearEquips($sameWeapons->pluck('id'));
		}
	}

	private function clearEquips($ids)
	{
		Auth::user()->inventories()->whereIn('id', $ids)->update(['equipped' => 0]);
	}

}