<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\CouponUser;
use App\Forms\CouponForm;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\FormBuilder;

class CouponController extends Controller
{

	public function index()
	{
		// Retrieve coupons
		$coupons = Coupon::all();

		// Build view
		return view('coupon.index', [
			'coupons' => $coupons,
		]);
	}

	public function create(FormBuilder $formBuilder)
	{
		// Build creation form
		$form = $formBuilder->create(CouponForm::class, [
			'method' => 'POST',
			'route'  => 'coupon.store',
		]);

		// Build form view
		return view('form', [
			'title'       => 'New coupon code form',
			'submit_text' => 'Create new coupon',
			'form'        => $form,
		]);
	}

	public function edit(FormBuilder $formBuilder, Coupon $coupon)
	{
		// Build edition form
		$form = $formBuilder->create(CouponForm::class, [
			'method' => 'PATCH',
			'route'  => ['coupon.update', $coupon],
			'model'  => $coupon,
		]);

		// Build edition view
		return view('form', [
			'title'       => "Updating coupon $coupon->code",
			'submit_text' => 'Update coupon',
			'form'        => $form,
		]);
	}

	public function store(Request $request)
	{
		// Validate coupon data
		$request->validate([
			'code'    => 'required',
			'reward'  => 'required|numeric|gt:0',
			'startAt' => 'required|date_format:Y-m-d H:i:s',
			'endAt'   => 'required|date_format:Y-m-d H:i:s',
		]);

		// Build coupon model
		$coupon = Coupon::make();

		$coupon->fill($request->all());

		$saved = $coupon->save();

		// Notify user of result
		if ($saved) {
			flash()->success("Coupon <strong>{$coupon->code}</strong> created!");
		} else {
			flash()->error("Coupon <strong>{$coupon->code}</strong> could not be saved!");
		}

		// Redirect to coupon index
		return redirect()->route('coupon.index');
	}


	public function update(Request $request, Coupon $coupon)
	{
		// Update coupon model
		$coupon->fill($request->all());

		$saved = $coupon->save();

		// Notify user of result
		if ($saved) {
			flash()->success("Coupon <strong>$coupon->code</strong> was updated successfully!");
		} else {
			flash()->error("Coupon <strong>$coupon->code</strong> could not be updated successfully!");
		}

		// Return to coupon index
		return redirect()->route('coupon.index');
	}

	public function use(Request $request)
	{
		// Validate if user provided a code
		if (!$request->has('code')) {
			flash()->error('Please input a coupon code!');

			return back();
		}

		// Query coupon information
		$coupon = Coupon::where('code', $request->input('code'))->first();

		// Check if coupon exists
		if (!$coupon) {
			flash()->error("Coupon {$request->input('code')} is invalid!")->important();

			return back();
		}

		// Check if user already used the coupon
		$used = Auth::user()->couponUses()->where('coupon_id', $coupon->id)->exists();
		if ($used) {
			flash()->error('You cannot use the same coupon <strong>more than 1 time</strong>!')->important();

			return back();
		}

		// Check if coupon has started
		if ($coupon->startAt->isFuture()) {
			flash()->error("Coupon {$coupon->code} is not usable yet, try again later!")->important();

			return back();
		}

		// Check if coupon is not expired
		if ($coupon->endAt->isPast()) {
			flash()->error("Coupon <strong>{$coupon->code}</strong> has expired!")->important();

			return back();
		}

		// Created coupon use
		$couponUse = CouponUser::make();

		$couponUse->user()->associate(Auth::user());
		$couponUse->coupon()->associate($coupon);

		$saved = $couponUse->save();

		// Check if coupon use was saved
		if (!$saved) {
			flash()->error('Something went wrong while generating the transaction for your coupon!')->important();

			return back();
		}

		// Create transaction with reward
		$transaction = Transaction::make();

		$transaction->value = $coupon->reward;
		$transaction->user()->associate(Auth::user());
		$transaction->owner()->associate($coupon);

		$transaction->save();

		// Notify user of success
		flash()->success("Coupon used successfully! <strong>You got rewarded with {$coupon->reward}</strong> <i class=\"fas fa-coins\"></i>.");

		return back();
	}

	public function delete(Coupon $coupon)
	{
		$deleted = $coupon->delete();

		// Notify user if coupon was deleted
		if ($deleted) {
			flash()->success("Coupon <strong>$coupon->code</strong> was deleted successfully!");
		} else {
			flash()->error("Coupon <strong>$coupon->code</strong> could not be deleted!");
		}

		return back();
	}
}
