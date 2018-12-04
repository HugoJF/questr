<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\InventoryPage;
use Tests\Browser\Pages\QuestFormPage;
use Tests\Browser\Pages\QuestListPage;
use Tests\Browser\Pages\ShopPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GuestTest extends DuskTestCase
{
	use DatabaseMigrations;
	use RefreshDatabase;

	public function test_guest_can_see_front_page()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new HomePage);
		});
	}

	public function test_guest_can_not_see_quest_list_page()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit((new QuestListPage)->url())
					->assertSee('403');
		});
	}

	public function test_guest_can_not_see_quest_form_page()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit((new QuestFormPage())->url())
					->assertSee('403');
		});
	}

	public function test_guest_can_not_see_shop_page()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit((new ShopPage)->url())
					->assertSee('403');
		});
	}

	public function test_guest_can_not_see_inventory_page()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit((new InventoryPage())->url())
					->assertSee('403');
		});
	}
}
