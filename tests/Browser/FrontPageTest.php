<?php

namespace Tests\Browser;

use App\Quest;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FrontPageTest extends DuskTestCase
{
	use DatabaseMigrations;
	use RefreshDatabase;

	public function test_front_page_is_loaded_properly()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new HomePage)
					->assertSee('Top quests');
		});
	}

	public function test_front_page_show_visible_quests()
	{
		$visibleQuestTitle = 'Visible quest';

		factory(Quest::class)->create([
			'title'  => $visibleQuestTitle,
			'hidden' => false,
		]);

		$this->browse(function (Browser $browser) use ($visibleQuestTitle) {
			$browser->visit(new HomePage)
					->assertSee($visibleQuestTitle);
		});
	}

	public function test_front_page_doesnt_show_hidden_quests()
	{
		$hiddenQuestTitle = 'Hidden quest';

		factory(Quest::class)->create([
			'title'  => $hiddenQuestTitle,
			'hidden' => true,
		]);

		$this->browse(function (Browser $browser) use ($hiddenQuestTitle) {
			$browser->visit(new HomePage)
					->assertDontSee($hiddenQuestTitle);
		});
	}

	public function test_front_page_has_sign_in_button()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new HomePage)
					->assertSee('Sign in');
		});
	}

	public function test_authed_user_sees_menu()
	{
		$user = factory(User::class)->create();

		$this->browse(function (Browser $browser) use ($user) {
			$browser->loginAs($user)
					->visit(new HomePage)
					->assertSee('Home')
					->assertSee('Quests')
					->assertSee('Shop')
					->assertSee('Inventory')
					->assertSee('Coupons');
		});
	}
}
