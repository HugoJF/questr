<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class CouponForm extends Form
{
	public function buildForm()
	{
		$this->code();
		$this->reward();
		$this->start();
		$this->end();
	}

	private function code()
	{
		$this->add('code', 'text', [
			'label'      => 'Coupon code',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Coupon code used by users'),
		]);
	}

	private function reward()
	{
		$this->add('reward', 'number', [
			'label'      => 'Quest reward',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Coupon reward'),
		]);
	}

	private function start()
	{
		$this->add('startAt', 'datetimepicker', [
			'label'      => 'Quest Start',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Coupon start date'),
		]);
	}

	private function end()
	{
		$this->add('endAt', 'datetimepicker', [
			'label'      => 'Quest end',
			'rules'      => ['required'],
			'help_block' => $this->getHelpBlock('Coupon expiry date'),
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
