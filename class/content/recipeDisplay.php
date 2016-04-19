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
		echo ('<div class="container">');

		if (!is_null($arguments['recipe']))
		{
			echo ("<h2>{$arguments['recipe']->name}</h2>");
			echo ("<p>{$arguments['recipe']->description}</p>");
			
			
			echo ("<table>");
			echo ("<tr><td><strong>Amount of attention</strong></td><td>: {$arguments['recipe']->amountOfAttention}<br></td></tr>");
			echo ("<tr><td><strong>Difficulty</strong></td><td>: {$arguments['recipe']->difficulty}<br></td></tr>");
			echo ("<tr><td><strong>Dish type</strong></td><td>: {$arguments['recipe']->dishType}<br></td></tr>");
			echo ("<tr><td><strong>Result type</strong></td><td>: {$arguments['recipe']->resultType}<br></td></tr>");
			echo ("<tr><td><strong>Manufacturing time</strong></td><td>: {$arguments['recipe']->manufacturingTime}<br></td></tr>");
			echo ("</table><br>");
			echo ("<strong>Ingredients</strong><br>");

			echo ("<ul>");
			foreach ($arguments['recipe']->ingredients as $ingr)
				echo ("<li>{$ingr->name}: {$ingr->amount} {$ingr->unitName}</li>");
			echo ("</ul>");

			$auth = new Authentication();
			if ($auth->isLoggedIn())
			{
				echo ("<p><a href='{$DomainPrefix}/addreview/{$arguments['recipe']->id}'><button type='button' class='btn btn-info'>Write review</button></a>");
				echo ("<br>");
				echo ("<p><a href='{$DomainPrefix}/modifyrecipe/{$arguments['recipe']->id}'><button type='button' class='btn btn-warning'>Modify recipe</button></a>");
				echo ("<br>");
				echo ("<p><a href='{$DomainPrefix}/deleterecipe/{$arguments['recipe']->id}'><button type='button' class='btn btn-danger'>Delete recipe</button></a>");
				
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
