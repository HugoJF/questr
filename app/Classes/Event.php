<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 1/3/2019
 * Time: 4:08 AM
 */

namespace App\Classes;


abstract class Event
{
	const TYPE_DAMAGE = 1;

	public abstract static function getType();

	public abstract static function build($raw);
}