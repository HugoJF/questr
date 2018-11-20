<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\ShopItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
	public function index(Request $request)
	{
		$items = ShopItem::query();

		if ($request->has('query')) {
			$items->where('market_hash_name', 'LIKE', '%' . $request->input('query') . '%');
		}

		$items = $items->paginate(12);

		return view('shop.index', [
			'items' => $items,
		]);
	}

	public function show(ShopItem $item)
	{
		return view('shop.show', [
			'item' => $item,
		]);
	}

	public function buy(Request $request, ShopItem $item)
	{
		$durations = config('app.durations');
		$duration = intval($request->input('duration'));

		if (!array_key_exists($duration, config('app.durations'))) {
			flash()->error('Invalid duration!')->important();

			return redirect()->back();
		}

		$cost = ceil($item->price * $duration * $durations[ $duration ]['multiplier']);


		if (Auth::user()->balance < $cost) {
			flash()->error('Insufficient balance to buy item!')->important();

			return redirect()->back();
		}
		$inv = Inventory::make();

		$inv->user()->associate(Auth::user());
		$inv->item()->associate($item);
		$inv->cost = $cost;
		$inv->equipped = false;
		$inv->synced = false;
		$inv->ends_at = Carbon::now()->addDay($duration);

		$inv->save();

		flash()->success("<strong>$item->market_hash_name</strong> successfully purchased for $duration days for <strong>$cost <i class=\"fas fa-coins\"></i></strong>");

		return redirect()->back();
	}
}
