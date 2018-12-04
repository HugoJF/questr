<?php

namespace Tests\Browser\Pages;

use App\User;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{
	/**
	 * Get the global element shortcuts for the site.
	 *
	 * @return array
	 */
	public static function siteElements()
	{
		return [
			'@element' => '#selector',
		];
	}
}
