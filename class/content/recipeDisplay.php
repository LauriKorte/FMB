<?php
require_once(__DIR__ . "/../site/content.php");
require_once(__DIR__ . "/../site/style.php");

class RecipeDisplayStyle extends Style
{
	public function displayHeader()
	{
		echo ("<hr />");
	}
	public function displayItem($arguments)
	{
		echo ("<div>");

		if (!is_null($arguments['recipe']))
		{
			echo ("<h2>{$arguments['recipe']->name}</h2>");
			//echo ("<li><ul>{$arguments['recipe']->description}</ul>");
			//echo ("<ul>{$arguments['recipe']->description}</ul></li>");
			echo ("<p>{$arguments['recipe']->description}</p>");
			echo ("<li>");
			foreach ($arguments['recipe']->ingredients as $ingr)
				echo ("<ul>{$ingr->name}: {$ingr->amount} {$ingr->unitName}</ul>");
			echo ("</li>");
		}
		else
		{
			echo("<p>There's no recipes here.</p>");
		}
	}
	public function displayItemEnd()
	{
		echo ("<div/>");
	}
	public function displayFooter()
	{
		echo ("");
	}
}

class RecipeDisplay extends Content
{
	public $recipe;
	public function __construct()
	{
		$this->style = new RecipeDisplayStyle();
	}
	
	public function displayContent()
	{
		$this->style->displayItem(array('recipe' => $this->recipe));
		$this->style->displayItemEnd();
	}
}
