<?php

namespace App\Forms;

use App\Classes\QuestMapper;
use Kris\LaravelFormBuilder\Form;

class QuestForm extends Form
{
	public function buildForm()
	{
		$this->title();
		$this->description();
		$this->type();
		$this->cost();
		$this->goal();
		$this->reward();
		$this->hidden();
		$this->code();
		$this->start();
		$this->end();
	}

	private function title()
	{
		$this->add('title', 'text', [
			'label'      => 'Quest title',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Short quest description'),
		]);
	}

	private function description()
	{
		$this->add('description', 'textarea', [
			'label'      => 'Quest description',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Detailed quest description'),
		]);
	}

	private function type()
	{
		$types = collect(QuestMapper::getTypes());
		$types = $types->mapWithKeys(function ($item) {
			return [$item => $item];
		});

		$this->add('type', 'select', [
			'choices'     => $types->toArray(),
			'empty_value' => '=== Select quest type ===',
		]);
	}

	private function cost()
	{
		$this->add('cost', 'number', [
			'label'      => 'Quest cost',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Cost to start quest'),
		]);
	}

	private function goal()
	{
		$this->add('goal', 'number', [
			'label'      => 'Quest goal',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Quest goal to finish it'),
		]);
	}

	private function reward()
	{
		$this->add('reward', 'number', [
			'label'      => 'Quest reward',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Quest reward on completion'),
		]);
	}

	private function start()
	{
		$this->add('startAt', 'datetimepicker', [
			'label'      => 'Quest Start',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('When quest will be open'),
		]);
	}

	private function end()
	{
		$this->add('endAt', 'datetimepicker', [
			'label'      => 'Quest end',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('When quest will close'),
		]);
	}

	private function getHelpBlock($text)
	{
		return [
			'tag'            => 'small',
			'helpBlockAttrs' => [
				'class' => 'form-text text-muted',
			],
			'text'           => $text ?? '',
		];
	}

	private function hidden()
	{
		$this->add('hidden', 'checkbox', [
			'label'      => 'Hidden quest',
			'help_block' => $this->getHelpBlock('Quests that can only be visible with direct link'),
		]);
	}

	private function code()
	{
		$this->add('code', 'text', [
			'label'      => 'Quest hidden code',
			'help_block' => $this->getHelpBlock('Code used to access hidden quest'),
		]);
	}
}
