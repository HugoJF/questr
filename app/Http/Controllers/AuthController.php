<?php

namespace App\Http\Controllers;

use App\Classes\SteamID;
use Invisnik\LaravelSteamAuth\SteamAuth;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	/**
	 * The SteamAuth instance.
	 *
	 * @var SteamAuth
	 */
	protected $steam;

	/**
	 * The redirect URL.
	 *
	 * @var string
	 */
	protected $redirectURL = '/';

	/**
	 * AuthController constructor.
	 *
	 * @param SteamAuth $steam
	 */
	public function __construct(SteamAuth $steam)
	{
		$this->steam = $steam;
	}

	/**
	 * Redirect the user to the authentication page
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function redirectToSteam()
	{
		return $this->steam->redirect();
	}

	/**
	 * Get user info and log in
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function handle()
	{
		if ($this->steam->validate()) {
			$info = $this->steam->getUserInfo();

			if (!is_null($info)) {
				$user = $this->findOrNewUser($info);

				Auth::login($user, true);

				return redirect($this->redirectURL); // redirect to site
			}
		}

		return $this->redirectToSteam();
	}

	/**
	 * Getting user by info or created if not exists
	 *
	 * @param $info
	 *
	 * @return User
	 */
	protected function findOrNewUser($info)
	{
		$user = User::where('steam_id', $info->steamID64)->first();

		if (!is_null($user)) {
			return $user;
		}

		$steamId = $this->normalizeSteamID($info->steamID64);

		return User::create([
			'username' => $info->personaname,
			'steam_id'  => $steamId,
		]);
	}

	protected function normalizeSteamID($steamID64)
	{
		try {
			$s = new SteamID($steamID64);

			if ($s->GetAccountType() !== SteamID::TypeIndividual) {
				throw new \InvalidArgumentException('We only support individual SteamIDs.');
			} else if (!$s->IsValid()) {
				throw new \InvalidArgumentException('Invalid SteamID.');
			}

			$s->SetAccountInstance(SteamID::DesktopInstance);
			$s->SetAccountUniverse(SteamID::UniversePublic);
		} catch (\InvalidArgumentException $e) {
			return null;
		}

		return $s->RenderSteam2();
	}
}