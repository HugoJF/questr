<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'username', 'steam_id', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public function questProgresses()
	{
		return $this->hasMany(QuestProgress::class);
	}

	public function inventories()
	{
		return $this->hasMany(Inventory::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}

	public function fakeTransactions()
	{
		return $this->morphMany(Transaction::class, 'owner');
	}

	public function couponUses()
	{
		return $this->hasMany(CouponUser::class);
	}

	public function getBalanceAttribute($refresh = false)
	{
		if ($refresh) {
			cache()->forget("user-$this->id-balance");
		}

		return $this->computeBalance();

		return cache()->remember("user-$this->id-balance", 5, function () {
			if ($this->isAdmin()) {
				return 9999999;
			} else {
				$this->computeBalance();
			}
		});
	}

	private function computeBalance()
	{
		$transactions = $this->transactions;

		$balance = $transactions->reduce(function ($carry, $item) {
			return $carry + $item->value;
		});

		return $balance;
	}

	public function isAdmin()
	{
		return $this->steam_id == 'STEAM_1:1:36509127';
	}
}
