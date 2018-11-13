<?php

namespace App\Http\Controllers;

use App\Inventory;
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
		if($inv->expired) {
			flash()->warning('You cannot equip expired items!')->important();

			return back();
		}

		$item = $inv->item;

		$joinedItems = Auth::user()->inventories()->join('shop_items', 'shop_items.id', '=', 'inventories.item_id');

		$sameWeapons = $joinedItems->where('shop_items.item_name', $item->item_name)->get(['inventories.id']);

		Auth::user()->inventories()->whereIn('id', $sameWeapons->pluck('id'))->update(['equipped' => 0]);

		$inv->equipped = true;

		$inv->save();

		flash()->success("Item <strong>{$item->market_hash_name}</strong> was equipped successfully!")->important();

		return redirect()->back();
	}

}
