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
	public function create(FormBuilder $formBuilder)
	{
		$form = $formBuilder->create(CouponForm::class, [
			'method' => 'POST',
			'url'    => route('coupon.store'),
		]);

		return view('form', [
			'title'       => 'New coupon code form',
			'submit_text' => 'Create new coupon',
			'form'        => $form,
		]);
	}

	public function store(Request $request)
	{
		$request->validate([
			'code'    => 'required',
			'reward'  => 'required|numeric|gt:0',
			'startAt' => 'required|date_format:Y-m-d h:i:s',
			'endAt'   => 'required|date_format:Y-m-d h:i:s',
		]);

		$coupon = Coupon::make();

		$coupon->fill($request->all());

		$coupon->save();

		flash()->success("Coupon {$coupon->code} created!");

		return redirect()->route('home');
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
		$transaction->owner()->assciate($coupon);

		$transaction->save();

		// Notify user of success
		flash()->success("Coupon used successfully! <strong>You got rewarded with {$coupon->reward}</strong> <i class=\"fas fa-coins\"></i>.");

		return back();
	}
}
