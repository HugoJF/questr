<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/1/2018
 * Time: 7:54 AM
 */

namespace App\Classes;


use App\Quest;
use App\QuestProgress;

abstract class BaseQuest
{
	protected $quest;
	protected $event;

	/*
	 * ABSTRACT METHODS
	 */

	public abstract function accepts(Event $event);

	public abstract function isComplete();

	public abstract function run();

	public abstract function getQuestProgress();

	public abstract function getQuestFilters();

	/*
	 * CONCRETE METHODS
	 */

	public function setQuest(Quest $quest)
	{
		$this->quest = $quest;
	}

	public function setEvent(Event $event)
	{
		$this->event = $event;
	}

	public function getUser()
	{
		if ($this->event) {
			return $this->event->getAttackerUser();
		} else {
			return null;
		}
	}


}