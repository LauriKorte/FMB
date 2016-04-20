<?php

require_once(__DIR__ . "/../site/style.php");

class RecipeListStyle extends Style
{
	
	public function displayHeader()
	{
		echo('<div class="container">');
		echo('<table class="table table-striped">');
	}
	public function displayItem($arguments)
	{
		echo ("<tr><td><a href='{$arguments['link']}'>Recipe: {$arguments['name']}</a></td>");
		echo ("</tr>");
	}
	public function displayItemEnd()
	{
	}
	public function displayFooter()
	{
		echo('</table>');
		echo('</div>');
	}
}
