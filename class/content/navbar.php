<?php

require_once(__DIR__ . "/../site/style.php");
class NavBarStyle extends Style
{
	public function displayHeader()
	{
		echo ("<hr />");
	}
	public function displayItem($arguments)
	{
		echo ("<div>");

		if (!is_null($arguments['links']))
		{
			foreach ($arguments['links'] as $text => $target)
			{
				echo ("<a href='{$target}'/>{$text}</a><br>");
			}
		}
	}
	public function displayItemEnd()
	{
		echo ("</div>");
	}
	public function displayFooter()
	{
		echo ("<hr />");
	}
}