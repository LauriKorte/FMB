<?php

require_once(__DIR__ . "/../site/style.php");
class PageLinksStyle extends Style
{
	public function displayHeader()
	{
	
	}
	public function displayItem($arguments)
	{
		echo ('<div class="container"><ul class="pagination">');

		if (!is_null($arguments['links']))
		{			
			foreach ($arguments['links'] as $text => $target)
			{
				echo ("<li><a href='{$target}'>{$text}</a></li>");
			}
		}
	}
	public function displayItemEnd()
	{
		echo ('</ul></div>');
	}
	public function displayFooter()
	{
	
	}
}
