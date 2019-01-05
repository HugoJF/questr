<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 1/3/2019
 * Time: 1:50 AM
 */

namespace App\Rankings;


abstract class BaseRanking
{
	/*
	 * ABSTRACT METHODS
	 */

	public abstract static function getStub();

	public abstract static function getTitle();

	public abstract static function getDescription();

	public abstract static function getRanking($amount = null);

	public abstract static function getView();

	/*
	 * CONCRETE METHODS
	 */

	public static function getUrl()
	{
		return static::buildUrl(static::getStub());
	}

	protected static function buildUrl($stub)
	{
		return route('ranking.process', $stub);
	}
}