<?php

class SiteMaster
{
	private $matches;

	public function __constructor()
	{
		$this->matches = array();
	}

	public function get($url)
	{
		foreach ($this->matches as $pattern => $func)
		{
			$match = array();
			if (preg_match($pattern, $url, $match))
			{
				return $func($match);
			}
		}
		return null;
	}

	public function addGetMatch($match, $function)
	{
		$this->matches[$match] = $function;
	}
}