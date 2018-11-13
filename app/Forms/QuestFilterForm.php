<?php

namespace App\Forms;

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
		$this->add('key', 'text', [
			'label'      => 'Filter Key',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Filter type'),
		]);
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
