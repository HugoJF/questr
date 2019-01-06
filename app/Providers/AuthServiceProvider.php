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
		'App\QuestFilter' => 'App\Policies\QuestFilterPolicy',
		'App\Inventory'   => 'App\Policies\InventoryPolicy',
		'App\ShopItem'    => 'App\Policies\ShopItemPolicy',
		'App\Coupon'      => 'App\Policies\CouponPolicy',
		'App\Model'       => 'App\Policies\ModelPolicy',
		'App\Quest'       => 'App\Policies\QuestPolicy',
		'App\User'        => 'App\Policies\UserPolicy',
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
