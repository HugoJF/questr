<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\ShopItem;
use App\Transaction;
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

		if ($request->has('conditions')) {
			$items->whereIn('condition', $request->input('conditions'));
		}

		if($request->has('skins')) {
			$items->whereIn('skin_name', $request->input('skins'));
		}

		if($request->has('weapons')) {
			$items->whereIn('item_name', $request->input('weapons'));
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
		// Cache values for buy durations
		$durations = config('app.durations');
		$duration = intval($request->input('duration'));

		// Check if user changed form values
		if (!array_key_exists($duration, config('app.durations'))) {
			flash()->error('Invalid duration!')->important();

			return redirect()->back();
		}

		// Recompute price with correct multiplier
		$cost = ceil($item->price * $duration * $durations[ $duration ]['multiplier']);

		// Check if user has enough balance to buy the item
		if (Auth::user()->balance < $cost) {
			flash()->error('Insufficient balance to buy item!')->important();

			return redirect()->back();
		}

		// Generate random float
		$float = $this->conditionNameToFloat($item->condition);

		// Generate item transaction
		$transaction = Transaction::make();

		$transaction->value = -$cost;
		$transaction->user()->associate(Auth::user());
		$transaction->owner()->associate($item);

		$transactionSaved = $transaction->save();

		// Check if transaction was correctly generated
		if (!$transactionSaved) {
			flash()->error('Error occurred while generating the transaction for your purchase!')->important();

			return back();
		}

		// Generate item
		$inv = Inventory::make();

		$inv->user()->associate(Auth::user());
		$inv->item()->associate($item);
		$inv->cost = $cost;
		$inv->equipped = false;
		$inv->synced = false;
		$inv->float = $float;
		$inv->ends_at = Carbon::now()->addDay($duration);

		$inv->save();

		// Send user back to previous page
		flash()->success("<strong>$item->market_hash_name</strong> successfully purchased for $duration days for <strong>$cost <i class=\"fas fa-coins\"></i></strong>");

		return back();
	}

	private function conditionNameToFloat($conditionName)
	{
		// Cache floats
		$floats = config('constants.floats');

		// Remove any white spaces
		$trimmed = trim($conditionName);

		// Generate float
		if (array_key_exists($trimmed, $floats)) {
			$multiplier = 1000000;

			list($low, $high) = $floats[ $trimmed ];

			$rand = rand($low * $multiplier, $high * $multiplier);

			return $rand / $multiplier;
		} else {
			return 2;
		}
	}
}
