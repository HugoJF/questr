<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	public function profile(User $user)
	{
		if (!$user->exists) {
			$user = Auth::user();
		}

		return view('profile', [
			'user' => $user,
		]);
	}
}
