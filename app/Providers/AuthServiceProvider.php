<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		'App\Model'       => 'App\Policies\ModelPolicy',
		'App\Quest'       => 'App\Policies\QuestPolicy',
		'App\QuestFilter' => 'App\Policies\QuestFilterPolicy',
		'App\ShopItem'    => 'App\Policies\ShopItemPolicy',
		'App\Inventory'   => 'App\Policies\InventoryPolicy',
		'App\Coupon'      => 'App\Policies\CouponPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();
	}
}
