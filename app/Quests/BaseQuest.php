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
	/** @var Quest */
	protected $quest;

	/** @var PlayerDamageEvent */
	protected $event;

	/*
	 * ABSTRACT METHODS
	 */

	public abstract function accepts(Event $event);

	public abstract function isComplete();

	public abstract function run();

	public abstract function getQuestProgress();

	public abstract static function getQuestFilters();

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

	// TODO: this should be removed
	public function getUser()
	{
		if ($this->event) {
			return $this->normalizeSteamID($this->event->getAttackerUser());
		} else {
			return null;
		}
	}

	public function acceptsCurrentServer()
	{
		$filter = $this->quest->questFilters()->where('key', 'server')->first();

		$server = $this->event->serverIp . ':' . $this->event->serverPort;

		if($filter) {
			return $filter->value == $server;
		} else {
			return true;
		}

	}
    



    
    
}
