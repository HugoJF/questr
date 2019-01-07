<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
	public function userList()
	{
		$users = User::latest()->get();

		return view('admin.userList', [
			'users' => $users,
		]);
	}
}
