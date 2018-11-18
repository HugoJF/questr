<?php

namespace App\Forms;

use App\Classes\QuestMapper;
use Kris\LaravelFormBuilder\Form;

class QuestFilterForm extends Form
{
	public function buildForm()
	{
		$this->key();
		$this->value();
	}

	private function key()
	{
		$quest = $this->getData('quest');
		if ($quest) {
			$choices = collect(QuestMapper::getFilterKeys($quest->type))->keyBy(function ($item) { return $item; });

			$this->add('key', 'select', [
				'choices' => $choices->toArray(),
				'empty_value' => '=== Select quest filter type ===',
			]);
		} else {
			$this->add('key', 'text', [
				'label'      => 'Filter Key',
				'rules'      => ['required'],
				'help_block' => $this->getHelpBlock('Filter type'),
			]);
		}
	}

	private function value()
	{
		$this->add('value', 'text', [
			'label'      => 'Filter Value',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Filter value'),
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
}
