<?php

require_once("site/style.php");

class RecipeListStyle extends Style
{
	public function displayHeader()
	{
		echo ("<h1>dA LIST</h1>");
	}
	public function displayItem($arguments)
	{
		echo ("<div><h2>{$arguments['name']}</h2>");
	}
	public function displayItemEnd()
	{
		echo ("</div>");
	}
	public function displayFooter()
	{
		echo ("");
	}
}