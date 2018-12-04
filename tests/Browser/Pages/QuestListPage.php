<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class QuestListPage extends Page
{
	/**
	 * Get the URL for the page.
	 *
	 * @return string
	 */
	public function url()
	{
		return '/quests';
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
				->assertSee('Available quests now!');
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
