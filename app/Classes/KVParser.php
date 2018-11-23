<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/9/2018
 * Time: 10:12 PM
 */

namespace App\Classes;


class KVParser
{
	static $STR_LOOKAHEAD = "\"";
	static $BLOCK_LOOKAHEAD = "{";

	public $index = 0;

	public $parsing = '';
	public $result = [];

	public function root()
	{
		$this->ws();
		while ($this->index < strlen($this->parsing) && $this->lookahead(static::$STR_LOOKAHEAD)) {
			$key = $this->str();
			$value = $this->block();
			$this->result[ $key ] = $value;
		}
	}

	public function str()
	{
		$this->ws();
		if (preg_match('/"(.*?)"/', substr($this->parsing, $this->index), $matches) === 1) {
			$this->index += strlen($matches[0]);
			$this->ws();
		} else {
			$this->error();
		}

		return $matches[1];
	}

	public function block()
	{
		$this->ob();
		$block = [];
		while ($this->lookahead(static::$STR_LOOKAHEAD)) {
			$key = $this->str();
			$value = null;
			if ($this->lookahead(static::$BLOCK_LOOKAHEAD)) {
				$value = $this->block();
			} else if ($this->lookahead(static::$STR_LOOKAHEAD)) {
				$value = $this->str();
			} else {
				throw new \Exception('Could not continue lookaheading around: ' . substr($this->parsing, $this->index, 20));
			}

			// If block is empty just set KV pair
			if(!array_key_exists($key, $block)) {
				$block[ $key ] = $value;
			} else {
				if($this->isAssoc($block[$key])) {
					// Convert associate array to sequential array to allow future pushes
					$old = $block[$key];
					$block[$key] = [$old, $value];
				} else {
					// Just push new value to key
					$block[$key][] = $value;
				}
			}
		}
		$this->cb();

		return $block;
	}

	public function ob()
	{
		$this->ws();
		if (preg_match('/\\{/', substr($this->parsing, $this->index), $matches) === 1) {
			$this->index += strlen($matches[0]);
			$this->ws();

		} else {
			$this->error();
		}
	}

	public function cb()
	{
		$this->ws();
		if (preg_match('/\\}/', substr($this->parsing, $this->index), $matches) === 1) {
			$this->index += strlen($matches[0]);
			$this->ws();
		} else {
			$this->error();
		}
	}

	public function ws()
	{
		if (preg_match('/[\\s\\n\\r]*/', substr($this->parsing, $this->index), $matches) === 1) {
			$this->index += strlen($matches[0]);
		} else {
			$this->error();
		}
	}

	public function lookahead($symbol)
	{
		return $this->parsing[ $this->index ] == $symbol;
	}

	public function error()
	{
		throw new \Exception('Error while parsing string');
	}

	function isAssoc(array $arr)
	{
		if (array() === $arr) return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}