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
	 	// Check if item is valid
		if ($inv->expired) {
			flash()->warning('You cannot equip expired items!')->important();

			return back();
		}

		// Check if user owns the item
		if ($inv->user->id != Auth::user()->id) {
			flash()->error('You cannot equip an item that is not owned by you!');

			return back();
		}

		$item = $inv->item;

		// Query inventory item information
		$joinedItems = Auth::user()->inventories()->join('shop_items', 'shop_items.id', '=', 'inventories.item_id');

		// Clean any equips with the same item name
		$this->clearWeaponEquips($item, clone $joinedItems);
		$this->clearKnifeEquips($item, $joinedItems);

		// Mark item as equipped
		$inv->equipped = true;

		$inv->save();

		// Notify user of result
		flash()->success("Item <strong>{$item->market_hash_name}</strong> was equipped successfully!")->important();

		return redirect()->back();
	}

	private function clearWeaponEquips(ShopItem $item, $joinedItems): void
	{
		// Retrieve every item from $joinedItems with the same name as $item
		$sameWeapons = $joinedItems->where('shop_items.item_name', $item->item_name)->get(['inventories.id']);

		$this->clearEquips($sameWeapons->pluck('id'));
	}

	private function clearKnifeEquips(ShopItem $item, $joinedItems)
	{
		// Retrieve knife names
		$knives = config('constants.knives');

		// Check if $item is a knife
		if (in_array($item->item_name, $knives)) {
			// Retrieve any item that is a knife
			$sameWeapons = $joinedItems->whereIn('shop_items.item_name', $knives)->get(['inventories.id']);

			$this->clearEquips($sameWeapons->pluck('id'));
		}
	}

	private function clearEquips($ids)
	{
		// Update database marking it as not equipped
		Auth::user()->inventories()->whereIn('id', $ids)->update(['equipped' => 0]);
	}

}