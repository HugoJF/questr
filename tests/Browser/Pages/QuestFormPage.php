<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class QuestFormPage extends Page
{
	/**
	 * Get the URL for the page.
	 *
	 * @return string
	 */
	public function url()
	{
		return '/quests/create';
	}

	/**
	 * Assert that the browser is on the page.
	 *
	 * @param  Browser $browser
	 *
	 * @return void
	 */
	public function assert(Browser $browser)
	{
		$browser->assertPathIs($this->url())
				->assertSee('New quest form');
	}

	/**
	 * Get the element shortcuts for the page.
	 *
	 * @return array
	 */
	public function elements()
	{
		return [
			'@element' => '#selector',
		];
	}
}
