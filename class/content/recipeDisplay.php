<?php
require_once(__DIR__ . "/../site/content.php");
require_once(__DIR__ . "/../site/style.php");
require_once(__DIR__ . "/../authentication.php");


class RecipeDisplayStyle extends Style
{
	public function displayHeader()
	{
		echo ("<hr />");
	}
	public function displayItem($arguments)
	{

		global $DomainPrefix;
		echo ('<div class="col-md-4">');

		if (!is_null($arguments['recipe']))
		{
			echo ("<h2>{$arguments['recipe']->name}</h2>");
			//echo ("<li><ul>{$arguments['recipe']->description}</ul>");
			//echo ("<ul>{$arguments['recipe']->description}</ul></li>");
			echo ("<p>{$arguments['recipe']->description}</p>");
			echo ("<ul>");
			foreach ($arguments['recipe']->ingredients as $ingr)
				echo ("<li>{$ingr->name}: {$ingr->amount} {$ingr->unitName}</li>");
			echo ("</ul>");

			$auth = new Authentication();
			if ($auth->isLoggedIn())
			{
				echo ("<a href='{$DomainPrefix}/deleterecipe/{$arguments['recipe']->id}'> Delete recipe </a>");
			}

		}
		else
		{
			echo("<p>There's no recipes here.</p>");
		}
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
