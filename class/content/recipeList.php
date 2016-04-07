<?php
require_once(__DIR__ . "/../site/content.php");
require_once("recipeListStyle.php");

class RecipeList extends Content
{
	public $recipes;
	
	public function __construct()
	{
		$this->style = new RecipeListStyle();
	}
	
	public function displayContent()
	{
		
		foreach ($this->recipes as $recipe)
		{
			$this->style->displayItem(array('name' => $recipe->name));
			$this->style->displayItemEnd();
		}
	}
}