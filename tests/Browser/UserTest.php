<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Browser\Pages\InventoryPage;
use Tests\Browser\Pages\QuestFormPage;
use Tests\Browser\Pages\QuestListPage;
use Tests\Browser\Pages\ShopPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends DuskTestCase
{
	use DatabaseMigrations;
	use RefreshDatabase;

	private function loginAsNormalUser(Browser $browser)
	{
		$user = factory(User::class)->create();

		$browser->loginAs($user);
	}

	public function test_authed_user_can_see_front_page()
	{
		$this->browse(function (Browser $browser) {
			$this->loginAsNormalUser($browser);

			$browser->visit(new ShopPage);
		});
	}

	public function test_authed_user_can_see_quests_page()
	{
		$this->browse(function (Browser $browser) {
			$this->loginAsNormalUser($browser);

			$browser->visit(new QuestListPage);
		});
	}

	public function test_authed_user_can_not_see_quest_form_page()
	{
		$this->browse(function (Browser $browser) {
			$this->loginAsNormalUser($browser);

			$browser->visit((new QuestFormPage)->url())
					->assertSee('403');
		});
	}

	public function test_authed_user_can_see_shop_page()
	{
		$this->browse(function (Browser $browser) {
			$this->loginAsNormalUser($browser);

			$browser->visit(new ShopPage);
		});
	}

	public function test_authed_user_can_see_inventory_page()
	{
		$this->browse(function (Browser $browser) {
			$this->loginAsNormalUser($browser);

			$browser->visit(new InventoryPage);
		});
	}
}
